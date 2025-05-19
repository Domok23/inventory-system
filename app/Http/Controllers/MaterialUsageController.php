<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialUsage;

class MaterialUsageController extends Controller
{
    public function index()
    {
        $usages = MaterialUsage::with('inventory', 'project')->orderBy('created_at', 'desc')->get();
        return view('material_usage.index', compact('usages'));
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
