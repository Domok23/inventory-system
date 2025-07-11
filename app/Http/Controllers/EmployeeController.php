<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Assuming you have an Employee model

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
            'name' => 'required',
            'position' => 'required',
        ]);
        Employee::create($request->only('name', 'position'));
        return redirect()->route('employees.index')->with('success', 'Employee berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
        ]);
        $employee->update($request->only('name', 'position'));
        return redirect()->route('employees.index')->with('success', 'Employee berhasil diupdate.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee berhasil dihapus.');
    }

    // View timing (dummy)
    public function timing(Employee $employee)
{
    $timings = $employee->timings()->with('project')->get();
    return view('employees.timing', compact('employee', 'timings'));
}
}