<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
        ]);
        $unit = Unit::create(['name' => $request->name]);
        return response()->json($unit);
    }

    public function json()
    {
        return Unit::select('id', 'name')->orderBy('name')->get();
    }
}