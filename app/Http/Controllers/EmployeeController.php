<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'notes' => 'nullable|string',
        ]);

        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'notes' => 'nullable|string',
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee berhasil diupdate.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee berhasil dihapus.');
    }

    // View timing
    public function timing(Employee $employee)
    {
        $timings = $employee->timings()->with('project')->get();
        return view('employees.timing', compact('employee', 'timings'));
    }
}
