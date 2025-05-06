@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit User</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (optional)</label>
            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin_logistic" {{ $user->role == 'admin_logistic' ? 'selected' : '' }}>Admin Logistic</option>
                <option value="admin_mascot" {{ $user->role == 'admin_mascot' ? 'selected' : '' }}>Admin Mascot</option>
                <option value="admin_costume" {{ $user->role == 'admin_costume' ? 'selected' : '' }}>Admin Costume</option>
                <option value="admin_finance" {{ $user->role == 'admin_finance' ? 'selected' : '' }}>Admin Finance</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
