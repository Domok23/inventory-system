@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New User</h2>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
            @error('username') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin_logistic">Admin Logistic</option>
                <option value="admin_mascot">Admin Mascot</option>
                <option value="admin_costume">Admin Costume</option>
                <option value="admin_finance">Admin Finance</option>
            </select>
            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
