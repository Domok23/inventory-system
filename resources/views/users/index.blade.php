@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="d-flex align-items-center mb-3 gap-2">
            <h3 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Users</h3>
            <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2">+ Add New User</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover" id="datatable">
            <thead>
                <tr>
                    <th class="text-center align-middle">ID</th>
                    <th class="text-center align-middle">Username</th>
                    <th class="text-center align-middle">Role</th>
                    <th class="text-center align-middle">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="text-center align-middle">{{ $user->id }}</td>
                        <td class="align-middle">{{ $user->username }}</td>
                        <td class="align-middle">{{ ucwords(str_replace('_', ' ', $user->role)) }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true
            });

            // SweetAlert for delete confirmation
            $('.btn-delete').on('click', function(e) {
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
