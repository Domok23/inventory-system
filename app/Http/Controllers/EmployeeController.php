<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['department'])
            ->withTrashed()
            ->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'email' => ['nullable', 'email', 'unique:employees,email'],
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'notes' => 'nullable|string',
        ]);

        Employee::create($request->only(['name', 'position', 'department_id', 'email', 'phone', 'hire_date', 'salary', 'status', 'notes']));
        return redirect()->route('employees.index')->with('success', 'Employee successfully added.');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'email' => ['nullable', 'email', 'unique:employees,email' . ($employee ? ',' . $employee->id : '')],
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'notes' => 'nullable|string',
        ]);

        $employee->update($request->only(['name', 'position', 'department_id', 'email', 'phone', 'hire_date', 'salary', 'status', 'notes']));
        return redirect()->route('employees.index')->with('success', 'Employee successfully updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee successfully deleted.');
    }

    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();
        return redirect()->route('employees.index')->with('success', 'Employee successfully restored.');
    }

    // View timing
    public function timing(Employee $employee)
    {
        $timings = $employee
            ->timings()
            ->with(['project.department'])
            ->paginate(50);
        return view('employees.timing', compact('employee', 'timings'));
    }
}
