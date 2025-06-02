<?php

namespace App\Http\Controllers;

use App\Models\GoodsIn;
use App\Models\GoodsOut;
use Illuminate\Http\Request;
use App\Models\MaterialUsage;
use App\Models\Inventory;
use App\Models\Project;

class MaterialUsageController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialUsage::with('inventory', 'project');

        // Apply filters
        if ($request->has('material') && $request->material !== null) {
            $query->where('inventory_id', $request->material);
        }

        if ($request->has('project') && $request->project !== null) {
            $query->where('project_id', $request->project);
        }

        $usages = $query->orderBy('created_at', 'desc')->get();

        // Pass data for filters
        $materials = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('material_usage.index', compact('usages', 'materials', 'projects'));
    }

    public function getByInventory(Request $request)
    {
        $inventory_id = $request->query('inventory_id');

        $usages = MaterialUsage::where('inventory_id', $inventory_id)
            ->with('project')
            ->get()
            ->map(function ($usage) {
                return [
                    'project_name' => $usage->project->name ?? 'N/A',
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