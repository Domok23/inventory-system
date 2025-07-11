@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employees Data</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Add New Employee</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->position }}</td>
                <td>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Delete</button>
                    </form>
                    <a href="{{ route('employees.timing', $employee) }}" class="btn btn-info btn-sm">View Timing</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection