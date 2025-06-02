@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-md-0 flex-shrink-0" style="font-size:1.5rem;">Material Requests</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-md-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('material_requests.create') }}"
                            class="btn btn-primary btn-sm flex-shrink-0">
                            + Request Material
                        </a>
                        <a href="{{ route('material_requests.bulk_create') }}" class="btn btn-warning btn-sm flex-shrink-0">
                            + Bulk Request
                        </a>
                        <button id="bulk-goods-out-btn" class="btn btn-info btn-sm flex-shrink-0">
                            Bulk Goods Out
                        </button>
                        <a href="{{ route('material_requests.export', request()->query()) }}" class="btn btn-success btn-sm flex-shrink-0">
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
                    <form id="filter-form" method="GET" action="{{ route('material_requests.index') }}" class="row g-2">
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
                            <select id="filter-status" name="status" class="form-select select2">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                    Delivered</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-requested-by" name="requested_by" class="form-select select2">
                                <option value="">All Requested By</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->username }}"
                                        {{ request('requested_by') == $user->username ? 'selected' : '' }}>
                                        {{ ucfirst($user->username) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="filter-requested-at" name="requested_at" class="form-control"
                                value="{{ request('requested_at') }}" placeholder="Requested At Date">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('material_requests.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-bordered table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Project</th>
                            <th>Material</th>
                            <th>Requested Qty</th>
                            <th>Requested By</th>
                            <th>Requested At</th>
                            <th>Status</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                            <tr id="row-{{ $req->id }}">
                                <td>
                                    @if ($req->status === 'approved')
                                        <input type="checkbox" class="select-row" id="checkbox-{{ $req->id }}"
                                            value="{{ $req->id }}">
                                    @endif
                                </td>
                                <td class="align-middle">{{ $req->project->name ?? '-' }}</td>
                                <td class="align-middle">{{ $req->inventory->name ?? '-' }}</td>
                                <td class="align-middle">{{ $req->qty }} {{ $req->inventory->unit ?? '-' }}</td>
                                <td class="align-middle">{{ ucfirst($req->requested_by) }}
                                    ({{ ucfirst($req->department) }})
                                </td>
                                <td class="align-middle">{{ $req->created_at->format('d-m-Y, H:i') }}</td>
                                <td class="align-middle">
                                    @if (in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                                        <form method="POST" action="{{ route('material_requests.update', $req->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="approved"
                                                    {{ $req->status === 'approved' ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="delivered"
                                                    {{ $req->status === 'delivered' ? 'selected' : '' }}>
                                                    Delivered</option>
                                            </select>
                                        </form>
                                    @else
                                        {{ ucfirst($req->status) }}
                                    @endif
                                </td>
                                <td class="align-middle">{{ $req->remark }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('material_requests.edit', $req->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        @if ($req->status === 'approved')
                                            <a href="{{ route('goods_out.create', $req->id) }}"
                                                class="btn btn-sm btn-success">Goods
                                                Out</a>
                                        @endif
                                        <form action="{{ route('material_requests.destroy', $req->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-danger btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
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
                responsive: true,
                desytroy: true,
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    }, // Kolom checkbox tidak dapat diurutkan
                ],
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            // Add placeholder support for input[type="date"]
            const dateInput = document.getElementById('filter-requested-at');
            if (dateInput) {
                dateInput.onfocus = function() {
                    this.type = 'date';
                };
                dateInput.onblur = function() {
                    if (!this.value) this.type = 'text';
                };
                if (!dateInput.value) dateInput.type = 'text';
            }

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

        $(document).ready(function() {
            $('#select-all').on('change', function() {
                $('.select-row').prop('checked', $(this).prop('checked'));
            });

            $('#bulk-goods-out-btn').on('click', function() {
                const selectedIds = $('.select-row:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire('Error', 'Please select at least one material request.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to process bulk goods out.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('goods_out.bulk') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                selected_ids: selectedIds,
                            },
                            success: function(response) {
                                Swal.fire('Success',
                                        'Bulk Goods Out processed successfully.',
                                        'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Error',
                                    'An error occurred while processing.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
