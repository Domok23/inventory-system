@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-md-0 flex-shrink-0" style="font-size:1.5rem;">Material Usage</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-md-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('material_usage.export', request()->query()) }}"
                            class="btn btn-success btn-sm flex-shrink-0">
                            Export to Excel
                        </a>
                    </div>
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

                <div class="mb-3">
                    <form id="filter-form" method="GET" action="{{ route('material_usage.index') }}" class="row g-2">
                        <div class="col-md-2">
                            <select id="filter-material" name="material" class="form-select select2">
                                <option value="">All Materials</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ request('material') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-project" name="project" class="form-select select2">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('material_usage.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
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
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
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
