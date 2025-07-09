@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-lg-0 flex-shrink-0" style="font-size:1.3rem;"><i
                            class="bi bi-box-arrow-in-down-left"></i> Material Requests</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-lg-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('material_requests.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
                            <i class="bi bi-plus-circle"></i> Create Request
                        </a>
                        <a href="{{ route('material_requests.bulk_create') }}" class="btn btn-info btn-sm flex-shrink-0">
                            <i class="bi bi-plus-circle"></i> Bulk Material Request
                        </a>
                        @if (auth()->user()->isLogisticAdmin())
                            <button id="bulk-goods-out-btn" class="btn btn-success btn-sm flex-shrink-0">
                                <i class="bi bi-box-arrow-in-right"></i> Bulk Goods Out
                            </button>
                        @endif
                        <a href="{{ route('material_requests.export', request()->query()) }}"
                            class="btn btn-outline-success btn-sm flex-shrink-0">
                            <i class="bi bi-file-earmark-excel"></i> Export
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {!! session('warning') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-3">
                    <form id="filter-form" method="GET" action="{{ route('material_requests.index') }}" class="row g-2">
                        <div class="col-lg-2">
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
                        <div class="col-lg-2">
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
                        <div class="col-lg-2">
                            <select id="filter-status" name="status" class="form-select select2">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                    Delivered</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>
                                    Canceled</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
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
                        <div class="col-lg-2">
                            <input type="text" id="filter-requested-at" name="requested_at" class="form-control"
                                value="{{ request('requested_at') }}" placeholder="Requested At Date" autocomplete="off">
                        </div>
                        <div class="col-lg-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('material_requests.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-bordered table-hover table-sm" id="datatable" data-material-request-table="1">
                    <thead class="align-middle text-nowrap">
                        <tr>
                            <th></th>
                            <th style="display:none">ID</th>
                            <th>Project</th>
                            <th>Material</th>
                            <th>Requested Qty</th>
                            <th>Remaining Qty
                                <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="The quantity of material requests that have not yet been processed for goods out."
                                    style="font-size: 0.8rem; cursor: pointer;"></i>
                            </th>
                            <th>Processed Qty
                                <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="The quantity of material requests that have already been processed and issued as goods out."
                                    style="font-size: 0.8rem; cursor: pointer;"></i>
                            </th>
                            <th>Requested By</th>
                            <th>Requested At</th>
                            <th>Status</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($requests as $req)
                            <tr id="row-{{ $req->id }}">
                                <td class="text-center">
                                    @if ($req->status === 'approved')
                                        <input type="checkbox" class="select-row" id="checkbox-{{ $req->id }}"
                                            value="{{ $req->id }}">
                                    @endif
                                </td>
                                <td style="display:none">{{ $req->id }}</td>
                                <td>{{ $req->project->name ?? '(No Project)' }}</td>
                                <td>{{ $req->inventory->name ?? '(No Material)' }}</td>
                                <td>{{ rtrim(rtrim(number_format($req->qty, 2, '.', ''), '0'), '.') }}
                                    {{ $req->inventory->unit ?? '(No Unit)' }}</td>
                                <td>{{ rtrim(rtrim(number_format($req->remaining_qty, 2, '.', ''), '0'), '.') }}
                                    {{ $req->inventory->unit ?? '(No Unit)' }}</td>
                                <td>{{ rtrim(rtrim(number_format($req->processed_qty, 2, '.', ''), '0'), '.') }}
                                    {{ $req->inventory->unit ?? '(No Unit)' }}</td>
                                <td>{{ ucfirst($req->requested_by) }}
                                    ({{ ucfirst($req->department) }})
                                </td>
                                <td>{{ $req->created_at?->format('Y-m-d, H:i') }}</td>
                                <td>
                                    @if (auth()->user()->isLogisticAdmin())
                                        <form method="POST" action="{{ route('material_requests.update', $req->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="filter_project"
                                                value="{{ request('project') }}">
                                            <input type="hidden" name="filter_material"
                                                value="{{ request('material') }}">
                                            <input type="hidden" name="filter_status" value="{{ request('status') }}">
                                            <input type="hidden" name="filter_requested_by"
                                                value="{{ request('requested_by') }}">
                                            <input type="hidden" name="filter_requested_at"
                                                value="{{ request('requested_at') }}">
                                            <select name="status" class="form-select form-select-sm status-select"
                                                onchange="this.form.submit()">
                                                <option value="pending"
                                                    {{ $req->status === 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="approved"
                                                    {{ $req->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="canceled"
                                                    {{ $req->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                                <option value="delivered"
                                                    {{ $req->status === 'delivered' ? 'selected' : '' }} disabled>Delivered
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span
                                            class="badge {{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $req->remark }}</td>
                                <td>
                                    <div class="d-flex flex-nowrap gap-1">
                                        @if ($req->status === 'approved' && $req->status !== 'canceled' && auth()->user()->isLogisticAdmin())
                                            <a href="{{ route('goods_out.create_with_id', $req->id) }}"
                                                class="btn btn-sm btn-success" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Goods Out"><i
                                                    class="bi bi-box-arrow-right"></i></a>
                                        @endif
                                        @if (
                                            $req->status === 'pending' &&
                                                (auth()->user()->username === $req->requested_by || auth()->user()->isLogisticAdmin()))
                                            <a href="{{ route('material_requests.edit', [$req->id] + request()->query()) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Edit"><i
                                                    class="bi bi-pencil-square"></i></a>
                                        @endif
                                        @if (
                                            $req->status !== 'canceled' &&
                                                (auth()->user()->isRequestOwner($req->requested_by) || auth()->user()->isSuperAdmin()))
                                            <form action="{{ route('material_requests.destroy', $req->id) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i
                                                        class="bi bi-trash3"></i></button>
                                            </form>
                                        @endif
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
@push('styles')
    <style>
        #datatable th {
            font-size: 0.90rem;
            white-space: nowrap;
            vertical-align: middle;
        }

        #datatable td {
            vertical-align: middle;
        }

        /* Batasi lebar kolom tertentu jika perlu */
        #datatable th,
        #datatable td {
            max-width: 170px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        select.form-select option:disabled {
            color: #999;
            cursor: not-allowed;
            background-color: #f8f9fa;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                destroy: true,
                stateSave: true,
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    }, // Kolom checkbox tidak dapat diurutkan
                    {
                        visible: false,
                        targets: 1
                    }, // Sembunyikan kolom ID di DataTable
                ],
                rowId: function(data) {
                    return 'row-' + data[1]; // data[1] adalah kolom ID
                },
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

            // Handle bulk goods out button
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

            flatpickr("#filter-requested-at", {
                dateFormat: "Y-m-d",
                allowInput: true,
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        window.authUser = {
            username: "{{ auth()->user()->username }}",
            is_logistic_admin: {{ auth()->user()->isLogisticAdmin() ? 'true' : 'false' }},
            is_super_admin: {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }}
        };
    </script>
@endpush
