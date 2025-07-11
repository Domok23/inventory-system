@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Employees Data</h1>
    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" class="form-control" required>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection