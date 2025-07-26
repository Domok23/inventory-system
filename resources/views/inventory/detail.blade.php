@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header text-white" style="background: linear-gradient(45deg, #8F12FE, #4A25AA);">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Inventory Details</h2>
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
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h5>{{ $inventory->name }}</h5>
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <th>Quantity</th>
                                <td>{{ $inventory->quantity }} {{ $inventory->unit }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $inventory->category ? $inventory->category->name : '-' }}</td>
                            </tr>
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic', 'admin_finance']))
                                <tr>
                                    <th>Unit Price</th>
                                    <td>
                                        {{ number_format($inventory->price ?? 0, 2, ',', '.') }}
                                        {{ $inventory->currency ? $inventory->currency->name : '' }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $inventory->supplier ? $inventory->supplier->name : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $inventory->location ? $inventory->location->name : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Remark</th>
                                <td>{!! $inventory->remark ?? '-' !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 text-center">
                        <h5>Material Image</h5>
                        @if ($inventory->img)
                            <a href="{{ asset('storage/' . $inventory->img) }}" data-fancybox="gallery"
                                data-caption="{{ $inventory->name }}">
                                <img src="{{ asset('storage/' . $inventory->img) }}" alt="Image"
                                    class="img-fluid img-hover rounded" style="max-height: 290px;">
                            </a>
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
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
    <div class="modal fade" id="goodsInModal" tabindex="-1" aria-labelledby="goodsInModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('goods_in.store_independent') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="goodsInModalLabel">Goods In</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required
                                min="0.01" step="any">
                        </div>
                        <div class="mb-3">
                            <label for="returned_at" class="form-label">Returned/In At <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="returned_at" id="returned_at" class="form-control" required>
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
    <div class="modal fade" id="goodsOutModal" tabindex="-1" aria-labelledby="goodsOutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('goods_out.store_independent') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="goodsOutModalLabel">Goods Out</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="" disabled selected>Select an option</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
