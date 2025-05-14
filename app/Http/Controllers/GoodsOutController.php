<?php

namespace App\Http\Controllers;

use App\Models\GoodsOut;
use App\Models\MaterialRequest;
use App\Models\Inventory;
use App\Models\Project;
use Illuminate\Http\Request;

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
}
