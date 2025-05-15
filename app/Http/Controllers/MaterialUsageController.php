<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialUsage;

class MaterialUsageController extends Controller
{
    public function index()
    {
        $usages = MaterialUsage::with('inventory', 'project')->get();
        return view('material_usage.index', compact('usages'));
    }
}