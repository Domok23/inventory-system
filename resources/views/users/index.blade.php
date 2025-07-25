@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-3 gap-2">
                    <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;"><i class="fas fa-user gradient-icon" style="font-size:1.5rem;"></i> Users</h2>
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2">+ New
                        User</a>
                </div>
                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- Table -->
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center">{{ $user->id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $user->role)) }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-x-circle"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    responsive: true,
                    select: true,
                    stateSave: true,
                });

                // SweetAlert for delete confirmation (use event delegation)
                $(document).on('click', '.btn-delete', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
