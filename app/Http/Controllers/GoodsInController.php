<?php

namespace App\Http\Controllers;

use App\Models\GoodsIn;
use App\Models\Project;
use App\Models\GoodsOut;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Helpers\MaterialUsageHelper;
use App\Models\User;

class GoodsInController extends Controller
{
    public function index(Request $request)
    {
        $query = GoodsIn::with(['goodsOut.inventory', 'goodsOut.project', 'inventory', 'project']);

        // Apply filters
        if ($request->has('material') && $request->material !== null) {
            $query->where('inventory_id', $request->material);
        }

        if ($request->has('project') && $request->project !== null) {
            $query->where('project_id', $request->project);
        }

        if ($request->has('qty') && $request->qty !== null) {
            $query->where('quantity', $request->qty);
        }

        if ($request->has('returned_by') && $request->returned_by !== null) {
            $query->where('returned_by', $request->returned_by);
        }

        if ($request->has('returned_at') && $request->returned_at !== null) {
            $query->whereDate('returned_at', $request->returned_at);
        }

        $goodsIns = $query->orderBy('created_at', 'desc')->get();

        // Pass data for filters
        $materials = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $quantities = GoodsIn::select('quantity')->distinct()->pluck('quantity');
        $users = User::orderBy('username')->get();

        return view('goods_in.index', compact('goodsIns', 'materials', 'projects', 'quantities', 'users'));
    }

    public function create($goods_out_id)
    {
        $goodsOut = GoodsOut::with('inventory', 'project')->findOrFail($goods_out_id);

        return view('goods_in.create', compact('goodsOut'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'goods_out_id' => 'required|exists:goods_out,id',
            'quantity' => 'required|numeric|min:0.01',
            'returned_at' => 'required',
            'remark' => 'nullable|string',
        ]);

        $goodsOut = GoodsOut::findOrFail($request->goods_out_id);

        // Validasi jumlah pengembalian
        if ($request->quantity > $goodsOut->remaining_quantity) {
            return back()->with('error', 'Returned quantity cannot exceed Goods Out quantity.');
        }

        // Tambahkan stok ke inventory
        $inventory = $goodsOut->inventory;
        if (!$inventory) {
            return back()->with('error', 'Inventory not found.');
        }

        // Validasi pastikan stok inventory tidak menjadi negatif
        if ($inventory->quantity + $request->quantity < 0) {
            return back()->with('error', 'Inventory stock cannot be negative.');
        }

        // Kurangi jumlah Goods Out
        $inventory->quantity += $request->quantity;
        $inventory->save();

        // Simpan Goods In (tambahkan inventory_id dan project_id)
        GoodsIn::create([
            'goods_out_id' => $goodsOut->id,
            'inventory_id' => $goodsOut->inventory_id,
            'project_id' => $goodsOut->project_id,
            'quantity' => $request->quantity,
            'returned_by' => auth()->user()->username,
            'returned_at' => $request->returned_at,
            'remark' => $request->remark,
        ]);

        // Sinkronkan data penggunaan material
        MaterialUsageHelper::sync($inventory->id, $goodsOut->project_id);

        return redirect()->route('goods_in.index')->with('success', 'Goods In recorded successfully.');
    }

    public function createIndependent()
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('goods_in.create_independent', compact('inventories', 'projects'));
    }

    public function storeIndependent(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'nullable|exists:projects,id',
            'quantity' => 'required|numeric|min:0.01',
            'returned_at' => 'required',
            'remark' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        // Validasi tambahan: Pastikan stok inventory tidak menjadi negatif
        if ($inventory->quantity + $request->quantity < 0) {
            return back()->with('error', 'Inventory stock cannot be negative.');
        }

        // Tambahkan stok ke inventory
        $inventory->quantity += $request->quantity;
        $inventory->save();

        // Simpan Goods In
        GoodsIn::create([
            'goods_out_id' => null, // Tidak terkait dengan Goods Out
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'quantity' => $request->quantity,
            'returned_by' => auth()->user()->username,
            'returned_at' => $request->returned_at,
            'remark' => $request->remark,
        ]);

        if ($request->filled('project_id')) {
            MaterialUsageHelper::sync($request->inventory_id, $request->project_id);
        }

        return redirect()->route('goods_in.index')->with('success', 'Goods In created successfully.');
    }

    public function bulkGoodsIn(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:goods_out,id',
        ]);

        $goodsOuts = GoodsOut::whereIn('id', $request->selected_ids)->get();

        foreach ($goodsOuts as $goodsOut) {
            $inventory = $goodsOut->inventory;

            // Tambahkan stok ke inventory
            $inventory->quantity += $goodsOut->quantity;
            $inventory->save();

            // Buat Goods In
            GoodsIn::create([
                'goods_out_id' => $goodsOut->id,
                'inventory_id' => $goodsOut->inventory_id,
                'project_id' => $goodsOut->project_id,
                'quantity' => $goodsOut->quantity,
                'returned_by' => auth()->user()->username,
                'returned_at' => now(),
                'remark' => 'Bulk Goods In',
            ]);
        }

        return redirect()->route('goods_out.index')->with('success', 'Bulk Goods In processed successfully.');
    }

    public function edit(GoodsIn $goods_in)
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('goods_in.edit', compact('goods_in', 'inventories', 'projects'));
    }

    public function update(Request $request, GoodsIn $goods_in)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'nullable|exists:projects,id',
            'quantity' => 'required|numeric|min:0.01',
            'returned_at' => 'required',
            'remark' => 'nullable|string',
        ]);

        $goods_in->update([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'quantity' => $request->quantity,
            'returned_at' => $request->returned_at,
            'remark' => $request->remark,
        ]);

        if ($request->filled('project_id')) {
            MaterialUsageHelper::sync($request->inventory_id, $request->project_id);
        }

        return redirect()->route('goods_in.index')->with('success', 'Goods In updated successfully.');
    }

    public function destroy(GoodsIn $goods_in)
    {
        // Cek apakah Goods In terkait dengan Goods Out
        if ($goods_in->goods_out_id) {
            return redirect()->route('goods_in.index')->with('error', 'Cannot delete Goods In that is linked to a Goods Out.');
        }

        // Hapus Goods In
        $goods_in->delete();

        // Sinkronkan Material Usage
        MaterialUsageHelper::sync($goods_in->inventory_id, $goods_in->project_id);

        return redirect()->route('goods_in.index')->with('success', 'Goods In deleted successfully.');
    }
}
