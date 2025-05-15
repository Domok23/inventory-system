@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Projects</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Add Project</a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table table-bordered" id="datatable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Qty</th>
                <th>Department</th>
                <th>Deadline</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->qty }}</td>
                <td>{{ $project->department }}</td>
                <td>{{ $project->deadline }}</td>
                <td>
                    @if($project->img)
                        <img src="{{ asset('storage/' . $project->img) }}" width="50">
                    @endif
                </td>
                <td>
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline-block;" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No projects yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();

            // SweetAlert for delete confirmation
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
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
