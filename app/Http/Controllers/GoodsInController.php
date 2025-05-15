<?php

namespace App\Http\Controllers;

use App\Models\GoodsIn;
use App\Models\GoodsOut;
use App\Models\Inventory;
use Illuminate\Http\Request;

class GoodsInController extends Controller
{
    public function index()
    {
        $goodsIns = GoodsIn::with('goodsOut.inventory', 'goodsOut.project')->get();
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
        ]);

        $goodsOut = GoodsOut::findOrFail($request->goods_out_id);

        // Validasi jumlah pengembalian
        if ($request->quantity > $goodsOut->quantity) {
            return back()->with('error', 'Returned quantity cannot exceed Goods Out quantity.');
        }

        // Tambahkan stok ke inventory
        $inventory = $goodsOut->inventory;
        $inventory->quantity += $request->quantity;
        $inventory->save();

        // Kurangi jumlah Goods Out
        $goodsOut->quantity -= $request->quantity;
        $goodsOut->save();

        // Simpan Goods In
        GoodsIn::create([
            'goods_out_id' => $goodsOut->id,
            'quantity' => $request->quantity,
            'returned_by' => auth()->user()->username,
            'returned_at' => $request->returned_at,
        ]);

        return redirect()->route('goods_in.index')->with('success', 'Goods In recorded successfully.');
    }
}
