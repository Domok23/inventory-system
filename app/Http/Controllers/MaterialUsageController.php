<?php

namespace App\Http\Controllers;

use App\Models\GoodsIn;
use App\Models\GoodsOut;
use Illuminate\Http\Request;
use App\Models\MaterialUsage;

class MaterialUsageController extends Controller
{
    public function index()
    {
        $usages = MaterialUsage::with('inventory', 'project')->orderBy('created_at', 'desc')->get();
        return view('material_usage.index', compact('usages'));
    }

    public function getByInventory(Request $request)
    {
        $inventory_id = $request->query('inventory_id');

        $usages = MaterialUsage::where('inventory_id', $inventory_id)
            ->with('project')
            ->get()
            ->map(function ($usage) {
                return [
                    'project_name' => $usage->project->name,
                    'goods_out_quantity' => GoodsOut::where('inventory_id', $usage->inventory_id)
                        ->where('project_id', $usage->project_id)
                        ->sum('quantity'),
                    'goods_in_quantity' => GoodsIn::where('inventory_id', $usage->inventory_id)
                        ->where('project_id', $usage->project_id)
                        ->sum('quantity'),
                    'used_quantity' => $usage->used_quantity,
                ];
            });

        return response()->json($usages);
    }

    public function destroy(MaterialUsage $material_usage)
    {
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->route('material_usage.index')->with('error', 'You are not authorized to delete this data.');
        }

        $material_usage->delete();
        return redirect()->route('material_usage.index')->with('success', 'Material usage deleted successfully.');
    }
}