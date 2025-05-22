@extends('layouts.app')
@section('content')
    <div class="container">
        <h3 style="font-size:1.5rem;">Material Usage</h3>
        <table class="table table-bordered table-hover" id="datatable">
            <thead>
                <tr>
                    <th></th>
                    <th>Material</th>
                    <th>Project</th>
                    <th>Used Quantity</th>
                    @if (auth()->user()->role === 'super_admin')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($usages as $usage)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $usage->inventory->name ?? '-' }}</td>
                        <td class="align-middle">{{ $usage->project->name ?? '-' }}</td>
                        <td class="align-middle" style="font-weight: bold;">{{ $usage->used_quantity }}
                            {{ $usage->inventory->unit ?? '-' }}</td>
                        @if (auth()->user()->role === 'super_admin')
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <form action="{{ route('material_usage.destroy', $usage->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        @endif
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
            $(document).on('click', '.btn-delete', function(e) {
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
