<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:locations,name']);
        $location = Location::create(['name' => $request->name]);
        return response()->json($location);
    }
}
