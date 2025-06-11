@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-lg-0 flex-shrink-0" style="font-size:1.5rem;"><i class="bi bi-arrow-up-circle"></i> Goods Out
                        Records</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-lg-auto d-flex flex-wrap gap-2">
                        @if (in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                            <a href="{{ route('goods_out.create_independent') }}"
                                class="btn btn-primary btn-sm flex-shrink-0">
                                <i class="bi bi-plus-circle"></i> Create Goods Out
                            </a>
                        @endif
                        <button id="bulk-goods-in-btn" class="btn btn-info btn-sm flex-shrink-0">
                            <i class="bi bi-box-arrow-in-right"></i> Bulk Goods In
                        </button>
                        <a href="{{ route('goods_out.export', request()->query()) }}"
                            class="btn btn-outline-success btn-sm flex-shrink-0">
                            <i class="bi bi-file-earmark-excel"></i> Export
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-3">
                    <form id="filter-form" method="GET" action="{{ route('goods_out.index') }}" class="row g-2">
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
                            <select id="filter-qty" name="qty" class="form-select select2">
                                <option value="">All Quantities</option>
                                @foreach ($quantities as $qty)
                                    <option value="{{ $qty }}" {{ request('qty') == $qty ? 'selected' : '' }}>
                                        {{ $qty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                            <input type="date" id="filter-requested-at" name="requested_at" class="form-control"
                                value="{{ request('requested_at') }}" placeholder="Proceed At Date">
                        </div>
                        <div class="col-lg-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('goods_out.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Tabel Data -->
                <table class="table table-bordered table-hover" id="datatable">
                    <thead class="align-middle">
                        <tr>
                            <th></th>
                            <th>Material</th>
                            <th>Qty</th>
                            <th>Remaining Qty</th>
                            <th>For Project</th>
                            <th>Requested By</th>
                            <th>Proceed At</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($goodsOuts as $goodsOut)
                            <tr>
                                <td>
                                    @if ($goodsOut->quantity > 0)
                                        @if (auth()->user()->username === $goodsOut->requested_by ||
                                                in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                            <input type="checkbox" class="select-row" id="checkbox-{{ $goodsOut->id }}"
                                                value="{{ $goodsOut->id }}">
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $goodsOut->inventory->name ?? '-' }}</td>
                                <td>{{ $goodsOut->quantity }} {{ $goodsOut->inventory->unit ?? '-' }}
                                </td>
                                <td>{{ $goodsOut->remaining_quantity }}
                                    {{ $goodsOut->inventory->unit ?? '-' }}</td>
                                <td>{{ $goodsOut->project->name ?? '-' }}</td>
                                <td>{{ ucfirst($goodsOut->requested_by) }}
                                    ({{ ucfirst($goodsOut->department) }})
                                </td>
                                <td>{{ \Carbon\Carbon::parse($goodsOut->created_at)->translatedFormat('d F Y, H:i') }}</td>
                                <td>
                                    @if ($goodsOut->remark)
                                        {{ $goodsOut->remark }}
                                    @else
                                        <span class="text-danger">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                        @if (in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                                            <a href="{{ route('goods_out.edit', $goodsOut->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            @if ($goodsOut->goodsIns->isEmpty())
                                                <form action="{{ route('goods_out.destroy', $goodsOut->id) }}"
                                                    method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-delete">Delete</button>
                                                </form>
                                            @endif
                                        @endif
                                        @if ($goodsOut->quantity > 0)
                                            @if (auth()->user()->username === $goodsOut->requested_by ||
                                                    in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                                <a href="{{ route('goods_in.create', ['goods_out_id' => $goodsOut->id]) }}"
                                                    class="btn btn-sm btn-info">
                                                    Goods In
                                                </a>
                                            @endif
                                        @endif
                                        @if (
                                            $goodsOut->material_request_id &&
                                                $goodsOut->materialRequest &&
                                                $goodsOut->materialRequest->qty > 0 &&
                                                in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                                            <a href="{{ route('goods_out.create', $goodsOut->material_request_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Process
                                            </a>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                stateSave: true,
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

            $('#bulk-goods-in-btn').on('click', function() {
                const selectedIds = $('.select-row:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire('Error', 'Please select at least one goods out.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to process bulk goods in.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('goods_in.bulk') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                selected_ids: selectedIds,
                            },
                            success: function(response) {
                                Swal.fire('Success',
                                        'Bulk Goods In processed successfully.',
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
