@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-lg-0 flex-shrink-0" style="font-size:1.3rem;"><i class="bi bi-box-arrow-in-right"></i> Goods
                        Out Records</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-lg-auto d-flex flex-wrap gap-2">
                        @if (auth()->user()->isLogisticAdmin())
                            <a href="{{ route('goods_out.create_independent') }}"
                                class="btn btn-primary btn-sm flex-shrink-0">
                                <i class="bi bi-plus-circle"></i> Create Goods Out
                            </a>
                        @endif
                        <button id="bulk-goods-in-btn" class="btn btn-info btn-sm flex-shrink-0">
                            <i class="bi bi-box-arrow-in-left"></i> Bulk Goods In
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
                    <thead class="align-middle text-nowrap">
                        <tr>
                            <th></th>
                            <th>Material</th>
                            <th>Qty (Goods Out)</th>
                            <th>
                                Remaining Qty
                                <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Remaining Qty column serves as an indicator to monitor the quantity of goods that have not been returned (Goods In) to inventory after the Goods Out process."
                                    style="font-size: 0.8rem;"></i>
                            </th>
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
                                <td>
                                    @if ($goodsOut->project)
                                        {{ $goodsOut->project->name }}
                                    @else
                                        <span class="text-secondary">No Project</span>
                                    @endif
                                </td>
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
                                        @if (auth()->user()->isLogisticAdmin())
                                            <a href="{{ route('goods_out.edit', $goodsOut->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            @if ($goodsOut->goodsIns->isEmpty())
                                                <form action="{{ route('goods_out.destroy', $goodsOut->id) }}"
                                                    method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        title="Delete"><i class="bi bi-trash3"></i></button>
                                                </form>
                                            @endif
                                        @endif
                                        @if (
                                            $goodsOut->material_request_id &&
                                                $goodsOut->materialRequest &&
                                                $goodsOut->materialRequest->qty > 0 &&
                                                in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                                            <a href="{{ route('goods_out.create_with_id', $goodsOut->material_request_id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Shortage">
                                                <i class="bi bi-exclamation-circle"></i>
                                            </a>
                                        @endif
                                        @if ($goodsOut->quantity > 0)
                                            @if (auth()->user()->username === $goodsOut->requested_by ||
                                                    in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                                <a href="{{ route('goods_in.create_with_id', ['goods_out_id' => $goodsOut->id]) }}"
                                                    class="btn btn-sm btn-info" title="Goods In">
                                                    <i class="bi bi-box-arrow-in-left"></i>
                                                </a>
                                            @endif
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
    <!-- Modal for Bulk Goods In -->
    <div class="modal fade" id="bulkGoodsInModal" tabindex="-1" aria-labelledby="bulkGoodsInModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkGoodsInModalLabel">Bulk Goods In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bulk-goods-in-form">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Goods Out Quantity</th>
                                    <th>Goods In Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="bulk-goods-in-table-body">
                                <!-- Rows will be dynamically added here -->
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submit-bulk-goods-in" class="btn btn-primary">Submit</button>
                </div>
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
    </style>
@endpush
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

                // Clear the modal table body
                $('#bulk-goods-in-table-body').empty();

                // Fetch data for selected goods out
                $.ajax({
                    url: "{{ route('goods_out.details') }}", // Gunakan endpoint yang benar
                    method: 'GET',
                    data: {
                        selected_ids: selectedIds
                    },
                    success: function(response) {
                        if (Array.isArray(response)) {
                            response.forEach(item => {
                                $('#bulk-goods-in-table-body').append(`
                        <tr>
                            <td>${item.material_name}</td>
                            <td>${item.goods_out_quantity}</td>
                            <td>
                                <input type="number" name="goods_in_quantities[${item.id}]" class="form-control"
                                    max="${item.goods_out_quantity}" min="0" required>
                            </td>
                        </tr>
                    `);
                            });

                            // Show the modal
                            $('#bulkGoodsInModal').modal('show');
                        } else {
                            Swal.fire('Error', 'Unexpected response format.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to fetch goods out details.', 'error');
                    }
                });
            });

            // Submit Bulk Goods In
            $('#submit-bulk-goods-in').on('click', function() {
                let isValid = true;

                // Validasi setiap input quantity
                $('#bulk-goods-in-table-body input[type="number"]').each(function() {
                    const value = parseFloat($(this).val());
                    if (isNaN(value) || value <= 0) {
                        isValid = false;
                        $(this).addClass('is-invalid'); // Tambahkan kelas untuk menandai error
                    } else {
                        $(this).removeClass('is-invalid'); // Hapus kelas jika valid
                    }
                });

                if (!isValid) {
                    Swal.fire('Error', 'Quantity must be greater than 0.', 'error');
                    return;
                }

                // Jika valid, kirim data ke server
                const formData = $('#bulk-goods-in-form').serialize();

                $.ajax({
                    url: "{{ route('goods_in.bulk') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Success', 'Bulk Goods In processed successfully.', 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'An error occurred while processing.', 'error');
                    }
                });
            });
        });
        $(document).ready(function() {
            // Inisialisasi Bootstrap Tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
