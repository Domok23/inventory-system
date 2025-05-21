<?php

namespace App\Http\Controllers;

use App\Models\GoodsIn;
use App\Models\Project;
use App\Models\GoodsOut;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Helpers\MaterialUsageHelper;

class GoodsInController extends Controller
{
    public function index()
    {
        $goodsIns = GoodsIn::with(['goodsOut.inventory', 'goodsOut.project', 'inventory', 'project'])->orderBy('created_at', 'desc')->get();
        return view('goods_in.index', compact('goodsIns'));
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
        $goods_in->delete();
        return redirect()->route('goods_in.index')->with('success', 'Goods In deleted successfully.');
    }
}
