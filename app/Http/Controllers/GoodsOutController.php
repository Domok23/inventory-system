<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\GoodsOut;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Helpers\MaterialUsageHelper;

class GoodsOutController extends Controller
{
    public function index()
    {
        $goodsOuts = GoodsOut::with('inventory', 'project', 'materialRequest')->orderBy('created_at', 'desc')->get();
        return view('goods_out.index', compact('goodsOuts'));
    }

    public function create($materialRequestId)
    {
        $materialRequest = MaterialRequest::with('inventory', 'project')->findOrFail($materialRequestId);
        $inventories = Inventory::orderBy('name')->get();
        return view('goods_out.create', compact('materialRequest', 'inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_request_id' => 'required|exists:material_requests,id',
            'quantity' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string',
        ]);

        $materialRequest = MaterialRequest::findOrFail($request->material_request_id);
        $inventory = $materialRequest->inventory;

        // Validasi quantity
        if ($request->quantity > $materialRequest->qty) {
            return back()->withInput()->with('error', 'Quantity cannot exceed the requested quantity.');
        }

        // Validasi tambahan: Pastikan stok inventory tidak menjadi negatif
        if ($request->quantity > $inventory->quantity) {
            return back()->withInput()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok di inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Perbarui status material request jika quantity habis
        if ($materialRequest->qty == 0) {
            $materialRequest->status = 'delivered';
        }

        // Simpan Goods Out
        $goodsOut = GoodsOut::create([
            'material_request_id' => $materialRequest->id,
            'inventory_id' => $inventory->id,
            'project_id' => $materialRequest->project_id,
            'requested_by' => $materialRequest->requested_by,
            'department' => $materialRequest->department,
            'quantity' => $request->quantity,
            'remark' => $request->remark,
        ]);

        // Update status material request jika selesai
        $materialRequest->qty -= $request->quantity;
        if ($materialRequest->qty == 0) {
            $materialRequest->status = 'delivered';
        }
        $materialRequest->save();

        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out processed successfully.');
    }

    public function createIndependent()
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('username')->get()->map(function ($user) {
            $user->department = match ($user->role) {
                'admin_mascot' => 'mascot',
                'admin_costume' => 'costume',
                'admin_logistic' => 'logistic',
                'super_admin' => 'management',
                default => 'general',
            };
            return $user;
        });
        return view('goods_out.create_independent', compact('inventories', 'projects', 'users'));
    }

    public function storeIndependent(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);
        $user = User::findOrFail($request->user_id);

        // Tentukan department berdasarkan role user
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'super_admin' => 'management',
            default => 'general',
        };

        // Validasi quantity
        if ($request->quantity > $inventory->quantity) {
            return back()->withInput()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok di inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Simpan Goods Out
        $goodsOut = GoodsOut::create([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'requested_by' => $user->username,
            'department' => $department, // Pastikan department diteruskan
            'quantity' => $request->quantity,
            'remark' => $request->remark,
        ]);

        MaterialUsageHelper::sync($request->inventory_id, $request->project_id);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out created successfully.');
    }

    public function edit($id)
    {
        $goodsOut = GoodsOut::with('inventory', 'project')->findOrFail($id);
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $users = User::all()->map(function ($user) {
            $user->department = match ($user->role) {
                'admin_mascot' => 'mascot',
                'admin_costume' => 'costume',
                'admin_logistic' => 'logistic',
                'super_admin' => 'management',
                default => 'general',
            };
            return $user;
        });

        return view('goods_out.edit', compact('goodsOut', 'inventories', 'projects', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string',
        ]);

        $goodsOut = GoodsOut::findOrFail($id);
        if (!$goodsOut) {
            return redirect()->route('goods_out.index')->with('error', 'Goods Out not found.');
        }
        $inventory = Inventory::findOrFail($request->inventory_id);
        $materialRequest = $goodsOut->materialRequest;

        $user = User::findOrFail($request->user_id);

        // Kembalikan stok lama ke inventory
        $oldQuantity = $goodsOut->quantity;
        $inventory->quantity += $oldQuantity;

        // Kembalikan quantity lama ke Material Request
        if ($materialRequest) {
            $materialRequest->qty += $oldQuantity;
        }

        // Validasi quantity baru
        if ($request->quantity > ($inventory->quantity + $oldQuantity)) {
            return back()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok dengan quantity baru
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Perbarui Material Request dengan quantity baru
        if ($materialRequest) {
            $materialRequest->qty -= $request->quantity;

            // Perbarui status jika quantity habis
            if ($materialRequest->qty == 0) {
                $materialRequest->status = 'delivered';
            } else {
                $materialRequest->status = 'approved';
            }

            $materialRequest->save();
        }

        // Perbarui Goods Out
        $goodsOut->update([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'requested_by' => $user->username,
            'department' => match ($user->role) {
                'admin_mascot' => 'mascot',
                'admin_costume' => 'costume',
                'admin_logistic' => 'logistic',
                'super_admin' => 'management',
                default => 'general',
            },
            'quantity' => $request->quantity,
            'remark' => $request->remark,
        ]);

        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out updated successfully.');
    }

    public function restore($id)
    {
        $goodsOut = GoodsOut::withTrashed()->findOrFail($id);

        // Restore Goods Out
        $goodsOut->restore();

        // Kurangi stok di inventory
        $inventory = $goodsOut->inventory;
        if ($inventory) {
            $inventory->quantity -= $goodsOut->quantity;
            $inventory->save();
        }

        // Sinkronkan Material Usage
        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out restored successfully.');
    }

    public function destroy($id)
    {
        $goodsOut = GoodsOut::findOrFail($id);

        // Cek apakah ada Goods In yang terkait
        if ($goodsOut->goodsIns()->exists()) {
            return redirect()->route('goods_out.index')->with('error', 'Cannot delete Goods Out with related Goods In.');
        }

        // Kembalikan stok ke inventory
        $inventory = $goodsOut->inventory;
        $inventory->quantity += $goodsOut->quantity;
        $inventory->save();

        // Soft delete Goods Out
        $goodsOut->delete();

        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out deleted successfully.');
    }
}