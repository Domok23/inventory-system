@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Inventory Details</h3>
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
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Name</th>
                                <td>{{ $inventory->name }}</td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td>{{ $inventory->quantity }} {{ $inventory->unit }}</td>
                            </tr>
                            <tr>
                                <th>Unit Price</th>
                                <td>{{ number_format($inventory->price, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $inventory->location }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 text-center">
                        <h5>Image</h5>
                        @if ($inventory->img)
                            <img src="{{ asset('storage/' . $inventory->img) }}" alt="Image" class="img-fluid rounded"
                                style="max-height: 300px;">
                        @else
                            <p class="text-muted">No Project Image</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="button" class="btn btn-primary my-1" data-bs-toggle="modal"
                    data-bs-target="#goodsInModal">Goods In</button>
                <button type="button" class="btn btn-success my-1" data-bs-toggle="modal"
                    data-bs-target="#goodsOutModal">Goods Out</button>
                <a href="#" class="btn btn-warning my-1" id="viewMaterialUsage">View Material Usage</a>
                <br>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary my-2">Back to Inventory</a>
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
                            <label for="project_id" class="form-label">Project (Optional)</label>
                            <select name="project_id" id="project_id" class="form-select">
                                <option value="" disabled selected>Select an option</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required
                                min="0.01" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="returned_at" class="form-label">Returned At</label>
                            <input type="date" name="returned_at" id="returned_at" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                            <select name="project_id" id="project_id" class="form-select" required>
                                <option value="" disabled selected>Select an option</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="" disabled selected>Select an option</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required
                                min="0.01" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                        <table class="table table-bordered" id="materialUsageDataTable">
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
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush
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
                    console.log(response); // Log respons dari server

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
            // Inisialisasi Select2 di modal Goods Out
            $('#goodsOutModal').on('shown.bs.modal', function() {
                $('#goodsOutModal select').select2({
                    dropdownParent: $('#goodsOutModal'), // Agar dropdown muncul di dalam modal
                    width: '100%', // Sesuaikan lebar dropdown
                    theme: 'bootstrap-5', // Gunakan tema Bootstrap 5
                    placeholder: 'Select an option',
                    allowClear: true
                });
            });

            // Inisialisasi Select2 di modal Goods In
            $('#goodsInModal').on('shown.bs.modal', function() {
                $('#goodsInModal select').select2({
                    dropdownParent: $('#goodsInModal'), // Agar dropdown muncul di dalam modal
                    width: '100%', // Sesuaikan lebar dropdown
                    theme: 'bootstrap-5', // Gunakan tema Bootstrap 5
                    placeholder: 'Select an option',
                    allowClear: true
                });
            });
        });
    </script>
@endpush
