<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\GoodsOut;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\MaterialRequest;

class GoodsOutController extends Controller
{
    public function index()
    {
        $goodsOuts = GoodsOut::with('inventory', 'project', 'materialRequest')->get();
        return view('goods_out.index', compact('goodsOuts'));
    }

    public function create($materialRequestId)
    {
        $materialRequest = MaterialRequest::with('inventory', 'project')->findOrFail($materialRequestId);
        $inventories = Inventory::all();
        return view('goods_out.create', compact('materialRequest', 'inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_request_id' => 'required|exists:material_requests,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $materialRequest = MaterialRequest::findOrFail($request->material_request_id);
        $inventory = $materialRequest->inventory;

        // Validasi quantity
        if ($request->quantity > $materialRequest->qty) {
            return back()->with('error', 'Quantity cannot exceed the requested quantity.');
        }

        if ($request->quantity > $inventory->quantity) {
            return back()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok di inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Perbarui status material request jika quantity habis
        if ($materialRequest->qty == 0) {
            $materialRequest->status = 'delivered';
        }

        // Simpan Goods Out
        GoodsOut::create([
            'material_request_id' => $materialRequest->id,
            'inventory_id' => $inventory->id,
            'project_id' => $materialRequest->project_id,
            'requested_by' => $materialRequest->requested_by,
            'department' => $materialRequest->department,
            'quantity' => $request->quantity,
        ]);

        // Update status material request jika selesai
        $materialRequest->qty -= $request->quantity;
        if ($materialRequest->qty == 0) {
            $materialRequest->status = 'delivered';
        }
        $materialRequest->save();

        return redirect()->route('goods_out.index')->with('success', 'Goods Out processed successfully.');
    }

    public function createIndependent()
    {
        $inventories = Inventory::all();
        $projects = Project::all();
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
        return view('goods_out.create_independent', compact('inventories', 'projects', 'users'));
    }

    public function storeIndependent(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric|min:0.01',
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
            return back()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok di inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Simpan Goods Out
        GoodsOut::create([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'requested_by' => $user->username,
            'department' => $department, // Pastikan department diteruskan
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out created successfully.');
    }

    public function edit($id)
    {
        $goodsOut = GoodsOut::with('inventory', 'project')->findOrFail($id);
        $inventories = Inventory::all();
        $projects = Project::all();
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
        ]);

        $goodsOut = GoodsOut::findOrFail($id);
        if (!$goodsOut) {
            return redirect()->route('goods_out.index')->with('error', 'Goods Out not found.');
        }
        $inventory = Inventory::findOrFail($request->inventory_id);

        $user = User::findOrFail($request->user_id);
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'super_admin' => 'management',
            default => 'general',
        };

        // Kembalikan stok lama ke inventory
        $oldQuantity = $goodsOut->quantity;
        $inventory->quantity += $oldQuantity;

        // Validasi quantity baru
        if ($request->quantity > ($inventory->quantity + $oldQuantity)) {
            return back()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok dengan quantity baru
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Perbarui Goods Out
        $goodsOut->update([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'requested_by' => $user->username,
            'department' => $user->department,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('goods_out.index')->with('success', 'Goods Out updated successfully.');
    }

    public function destroy($id)
    {
        $goodsOut = GoodsOut::findOrFail($id);

        // Kembalikan stok ke inventory
        $inventory = $goodsOut->inventory;
        $inventory->quantity += $goodsOut->quantity;
        $inventory->save();

        // Hapus Goods Out
        $goodsOut->delete();

        return redirect()->route('goods_out.index')->with('success', 'Goods Out deleted successfully.');
    }
}
