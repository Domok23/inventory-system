<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->input('department_name');
        $exists = Department::where('name', $name)->exists();

        if ($exists) {
            $msg = "Department '$name' already exists.";
            if ($request->ajax()) {
                return response()->json(['message' => $msg], 422);
            }
            return back()
                ->withErrors(['department_name' => $msg])
                ->withInput();
        }

        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:departments,name',
        ]);
        $department = Department::create(['name' => $validated['department_name']]);

        if ($request->ajax()) {
            return response()->json($department);
        }
        return back()->with('success', 'Department added!');
    }
}
