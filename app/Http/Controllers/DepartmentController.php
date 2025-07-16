<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);
        $department = Department::create($validated);

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json($department);
        }
        return back()->with('success', 'Department added!');
    }
}
