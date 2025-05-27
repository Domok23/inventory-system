@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Edit User</h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username"
                                value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label mb-0">New Password</label>
                            <small class="text-muted mb-2">(Leave blank to keep current password)</small>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control">
                                <button type="button" class="btn btn-secondary toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="super_admin"
                                    {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>
                                    Super Admin</option>
                                <option value="admin_logistic"
                                    {{ old('role', $user->role) == 'admin_logistic' ? 'selected' : '' }}>Admin Logistic
                                </option>
                                <option value="admin_mascot"
                                    {{ old('role', $user->role) == 'admin_mascot' ? 'selected' : '' }}>
                                    Admin Mascot</option>
                                <option value="admin_costume"
                                    {{ old('role', $user->role) == 'admin_costume' ? 'selected' : '' }}>Admin Costume
                                </option>
                                <option value="admin_finance"
                                    {{ old('role', $user->role) == 'admin_finance' ? 'selected' : '' }}>Admin Finance
                                </option>
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordButtons = document.querySelectorAll('.toggle-password');

            togglePasswordButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const passwordInput = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
@endpush
