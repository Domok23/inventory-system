@extends('layouts.app')

@push('styles')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #8F12FE, #4A25AA);
        }

        .info-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8F12FE;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .action-btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .btn-primary.action-btn {
            background: linear-gradient(135deg, #8F12FE, #6610f2);
            border-color: #8F12FE;
        }

        .btn-success.action-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .btn-info.action-btn {
            background: linear-gradient(135deg, #17a2b8, #20c997);
        }

        .btn-warning.action-btn {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529;
        }

        .btn-secondary.action-btn {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .image-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-placeholder {
            color: #adb5bd;
            font-size: 4rem;
        }

        .detail-table th {
            background-color: #f8f9fc;
            font-weight: 600;
            color: #495057;
            border-color: #e3e6f0;
            padding: 1rem;
        }

        .detail-table td {
            padding: 1rem;
            border-color: #e3e6f0;
        }

        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .quantity-badge {
            background: linear-gradient(135deg, #8F12FE, #6610f2);
            color: white;
        }

        .price-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .breadcrumb .breadcrumb-item,
        .breadcrumb .breadcrumb-item a {
            color: #8F12FE !important;
            /* Warna ungu branding */
            font-weight: 500;
            text-decoration: none;
        }

        .breadcrumb .breadcrumb-item.active {
            color: #4A25AA !important;
            /* Ungu lebih gelap untuk item aktif */
        }

        @media (max-width: 768px) {
            .action-btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}"
                                class="text-decoration-none">Inventory</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $inventory->name }}</li>
                    </ol>
                </nav>

                <!-- Main Card -->
                <div class="card info-card shadow-lg">
                    <div class="card-header gradient-bg text-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cube me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h4 class="mb-0 fw-bold">{{ $inventory->name }}</h4>
                                    <small class="opacity-75">Inventory Details</small>
                                </div>
                            </div>
                            <div class="text-end">
                                @if ($inventory->category)
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        {{ $inventory->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                            <i class="fas fa-times-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Details Section -->
                            <div class="col-lg-6 mb-4">
                                <h5 class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    Material Details
                                </h5>
                                <div class="table-responsive">
                                    <table class="table detail-table table-hover">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 40%;">
                                                    <i class="fas fa-tag me-2 text-muted"></i>Name
                                                </th>
                                                <td class="fw-semibold">{{ $inventory->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <i class="fas fa-layer-group me-2 text-muted"></i>Category
                                                </th>
                                                <td>
                                                    @if ($inventory->category)
                                                        <span
                                                            class="badge badge-custom bg-primary">{{ $inventory->category->name }}</span>
                                                    @else
                                                        <span class="text-muted">No Category</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <i class="fas fa-boxes me-2 text-muted"></i>Stock Quantity
                                                </th>
                                                <td>
                                                    <span class="badge badge-custom quantity-badge">
                                                        {{ number_format($inventory->quantity, 2) }}
                                                        {{ $inventory->unit ?? '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic', 'admin_finance']))
                                                <tr>
                                                    <th scope="row">
                                                        <i class="fas fa-dollar-sign me-2 text-muted"></i>Unit Price
                                                    </th>
                                                    <td>
                                                        <span class="badge badge-custom price-badge">
                                                            {{ number_format($inventory->price ?? 0, 2) }}
                                                            {{ $inventory->currency ? $inventory->currency->name : '' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th scope="row">
                                                    <i class="fas fa-truck me-2 text-muted"></i>Supplier
                                                </th>
                                                <td>{{ $inventory->supplier ? $inventory->supplier->name : 'No Supplier' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>Location
                                                </th>
                                                <td>{{ $inventory->location ? $inventory->location->name : 'No Location' }}
                                                </td>
                                            </tr>
                                            @if ($inventory->remark)
                                                <tr>
                                                    <th scope="row">
                                                        <i class="fas fa-sticky-note me-2 text-muted"></i>Remark
                                                    </th>
                                                    <td>{!! $inventory->remark !!}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Image Section -->
                            <div class="col-lg-6 mb-4">
                                <h5 class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-image me-2 text-primary"></i>
                                    Material Image
                                </h5>
                                <div class="image-container">
                                    @if ($inventory->img)
                                        <a href="{{ asset('storage/' . $inventory->img) }}" data-fancybox="gallery"
                                            data-caption="{{ $inventory->name }}">
                                            <img src="{{ asset('storage/' . $inventory->img) }}"
                                                class="img-fluid rounded shadow-sm" alt="{{ $inventory->name }}"
                                                style="max-height: 300px; width: 100%; object-fit: cover;">
                                        </a>
                                    @else
                                        <div class="text-center p-5">
                                            <i class="fas fa-image image-placeholder"></i>
                                            <p class="text-muted mt-3">No Image Available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('material_requests.create', ['material_id' => $inventory->id]) }}"
                            class="btn btn-info my-1">
                            Request this Material
                        </a>
                        <button type="button" class="btn btn-primary my-1" data-bs-toggle="modal"
                            data-bs-target="#goodsInModal">Goods In</button>
                        @if (auth()->user()->isLogisticAdmin())
                            <button type="button" class="btn btn-success my-1" data-bs-toggle="modal"
                                data-bs-target="#goodsOutModal">Goods Out</button>
                        @endif
                        <a href="#" class="btn btn-warning my-1" id="viewMaterialUsage">View Material Usage</a>
                        <br>
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary my-2"><i
                                class="bi bi-arrow-left-short"></i> Back to Inventory</a>
                    </div>
                </div>
            </div>
            <!-- Modal for Goods In -->
            <div class="modal fade" id="goodsInModal" tabindex="-1" aria-labelledby="goodsInModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('goods_in.store_independent') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="goodsInModalLabel">Goods In</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                                <div class="mb-3">
                                    <label for="project_id" class="form-label">Project</label>
                                    <select name="project_id" id="project_id" class="form-select">
                                        <option value="" class="text-muted">No Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required
                                        min="0.01" step="any">
                                </div>
                                <div class="mb-3">
                                    <label for="returned_at" class="form-label">Returned/In At <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="returned_at" id="returned_at" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="remark" class="form-label">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="goodsin-submit-btn">
                                    <span class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                        aria-hidden="true"></span>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for Goods Out -->
            <div class="modal fade" id="goodsOutModal" tabindex="-1" aria-labelledby="goodsOutModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('goods_out.store_independent') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="goodsOutModalLabel">Goods Out</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                                <div class="mb-3">
                                    <label for="project_id" class="form-label">Project</label>
                                    <select name="project_id" id="project_id" class="form-select">
                                        <option value="" class="text-muted">No Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User <span
                                            class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="" disabled selected>Select an option</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required
                                        min="0.01" step="any">
                                </div>
                                <div class="mb-3">
                                    <label for="remark" class="form-label">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="goodsout-submit-btn">
                                    <span class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                        aria-hidden="true"></span>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for Material Usage -->
            <div class="modal fade" id="materialUsageModal" tabindex="-1" aria-labelledby="materialUsageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="materialUsageModalLabel">Material Usage</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="materialUsageDataTable">
                                    <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Goods Out Quantity</th>
                                            <th>Goods In Quantity</th>
                                            <th>Used Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody id="materialUsageTable">
                                        <!-- Data akan dimuat melalui AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection


        @push('scripts')
            <script>
                $(document).on('click', '#viewMaterialUsage', function(e) {
                    e.preventDefault();
                    const inventoryId = {{ $inventory->id }};

                    // Kosongkan tabel sebelum memuat data baru
                    $('#materialUsageTable').empty();

                    // Fetch data melalui AJAX
                    $.ajax({
                        url: "{{ route('material_usage.get_by_inventory') }}",
                        method: "GET",
                        data: {
                            inventory_id: inventoryId
                        },
                        success: function(response) {
                            // Kosongkan tabel sebelum memuat data baru
                            $('#materialUsageTable').empty();

                            if (Array.isArray(response)) {
                                response.forEach(function(usage) {
                                    $('#materialUsageTable').append(`
                                <tr>
                                    <td>${usage.project_name}</td>
                                    <td>${usage.goods_out_quantity}</td>
                                    <td>${usage.goods_in_quantity}</td>
                                    <td>${usage.used_quantity}</td>
                                </tr>
                            `);
                                });

                                // Inisialisasi DataTables setelah data dimuat
                                $('#materialUsageDataTable').DataTable({
                                    destroy: true, // Hapus inisialisasi sebelumnya jika ada
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                });
                            } else {
                                console.error('Unexpected response format:', response);
                                alert('Failed to load material usage data. Please try again.');
                            }

                            // Tampilkan modal
                            $('#materialUsageModal').modal('show');
                        },
                        error: function(xhr) {
                            alert('Failed to load material usage data.');
                        }
                    });
                });

                $(document).ready(function() {
                    // Simpan isi awal tombol
                    const goodsInBtn = $('#goodsin-submit-btn');
                    const goodsInBtnHtml = goodsInBtn.html();
                    const goodsOutBtn = $('#goodsout-submit-btn');
                    const goodsOutBtnHtml = goodsOutBtn.html();

                    // Handle submit Goods In
                    $('#goodsInModal form').on('submit', function() {
                        goodsInBtn.prop('disabled', true);
                        goodsInBtn.find('.spinner-border').removeClass('d-none');
                    });

                    // Handle submit Goods Out
                    $('#goodsOutModal form').on('submit', function() {
                        goodsOutBtn.prop('disabled', true);
                        goodsOutBtn.find('.spinner-border').removeClass('d-none');
                    });

                    // Reset tombol saat modal dibuka ulang
                    $('#goodsInModal').on('shown.bs.modal', function() {
                        goodsInBtn.prop('disabled', false);
                        goodsInBtn.html(goodsInBtnHtml);
                    });
                    $('#goodsOutModal').on('shown.bs.modal', function() {
                        goodsOutBtn.prop('disabled', false);
                        goodsOutBtn.html(goodsOutBtnHtml);
                    });

                    // Inisialisasi Select2 di modal Goods Out
                    $('#goodsOutModal').on('shown.bs.modal', function() {
                        $('#goodsOutModal select').select2({
                            dropdownParent: $('#goodsOutModal'), // Agar dropdown muncul di dalam modal
                            width: '100%', // Sesuaikan lebar dropdown
                            theme: 'bootstrap-5', // Gunakan tema Bootstrap 5
                            allowClear: true
                        });
                    });

                    // Inisialisasi Select2 di modal Goods In
                    $('#goodsInModal').on('shown.bs.modal', function() {
                        $('#goodsInModal select').select2({
                            dropdownParent: $('#goodsInModal'), // Agar dropdown muncul di dalam modal
                            width: '100%', // Sesuaikan lebar dropdown
                            theme: 'bootstrap-5', // Gunakan tema Bootstrap 5
                            allowClear: true
                        });
                    });
                });

                document.addEventListener('DOMContentLoaded', function() {
                    Fancybox.bind("[data-fancybox='gallery']", {
                        Toolbar: {
                            display: [{
                                    id: "counter",
                                    position: "center"
                                }, // Menampilkan penghitung gambar
                                "zoom", // Tombol zoom
                                "download", // Tombol download
                                "close" // Tombol close
                            ],
                        },
                        Thumbs: false, // Nonaktifkan thumbnail jika tidak diperlukan
                        Image: {
                            zoom: true, // Aktifkan fitur zoom
                        },
                        Hash: false, // Nonaktifkan fitur History API
                    });
                });
            </script>
        @endpush
