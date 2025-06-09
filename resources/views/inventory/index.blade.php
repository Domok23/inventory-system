@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Card Wrapper -->
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-md-0 flex-shrink-0" style="font-size:1.5rem;">Inventory List</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-md-auto d-flex flex-wrap gap-2">
                        @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
                                + New Inventory
                            </a>
                            <button type="button" class="btn btn-outline-success btn-sm flex-shrink-0"
                                data-bs-toggle="modal" data-bs-target="#importModal">
                                Import Inventory via XLS
                            </button>
                        @endif
                        <a href="{{ route('inventory.export', request()->query()) }}"
                            class="btn btn-success btn-sm flex-shrink-0">
                            Export to XLS
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
                @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif


                <div class="mb-3">
                    <form method="GET" action="{{ route('inventory.index') }}" class="row g-2">
                        <div class="col-md-2">
                            <select name="category" id="category" class="form-select select2">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic', 'admin_finance']))
                            <div class="col-md-2">
                                <select name="currency" id="currency" class="form-select select2">
                                    <option value="">All Currencies</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            {{ request('currency') == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-2">
                            <select name="supplier" id="supplier" class="form-select select2">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier }}"
                                        {{ request('supplier') == $supplier ? 'selected' : '' }}>
                                        {{ $supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="location" id="location" class="form-select select2">
                                <option value="">All Locations</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location }}"
                                        {{ request('location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-hover table-bordered" id="datatable">
                    <thead class="align-middle">
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic', 'admin_finance']))
                                <th>Unit Price</th>
                            @endif
                            <th>Supplier</th>
                            <th>Location</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($inventories as $inventory)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $inventory->name }}</td>
                                <td>
                                    {{ $inventory->category ? $inventory->category->name : '-' }}
                                </td>
                                <td>{{ $inventory->quantity }} {{ $inventory->unit }}</td>
                                @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic', 'admin_finance']))
                                    <td>
                                        {{ number_format($inventory->price, 2, ',', '.') }}
                                        {{ $inventory->currency ? $inventory->currency->name : '' }}
                                    </td>
                                @endif
                                <td>{{ $inventory->supplier ?? '-' }}</td>
                                <td>{{ $inventory->location ?? '-' }}</td>
                                <td>{{ $inventory->remark ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                            <a href="{{ route('inventory.edit', $inventory->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('inventory.detail', ['id' => $inventory->id]) }}"
                                            class="btn btn-sm btn-success">Detail</a>
                                        <button type="button" class="btn btn-sm btn-info btn-show-image"
                                            data-bs-toggle="modal" data-bs-target="#imageModal"
                                            data-img="{{ $inventory->img ? asset('storage/' . $inventory->img) : '' }}"
                                            data-qrcode="{{ $inventory->qrcode_path ? asset('storage/' . $inventory->qrcode_path) : '' }}"
                                            data-name="{{ $inventory->name }}">
                                            Show
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal Show Image -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Inventory Image & QR Code </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div id="img-container" class="mb-3"></div>
                                <div id="qr-container" class="mb-3"></div>
                                <button id="download-qr-btn" class="btn btn-outline-primary btn-sm mb-3"
                                    style="display:none;">Download
                                    QR</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Import Inventory via XLS -->
                <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('inventory.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="importModalLabel">Import Inventory</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="xls_file" class="form-label">Upload XLS File <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="xls_file" id="xls_file" class="form-control"
                                            required accept=".xls,.xlsx">
                                    </div>
                                    <p class="text-muted">
                                        Template kolom: <code>Name, Category, Quantity, Unit, Price, Currency, Supplier, Location</code>
                                    </p>
                                    <a href="{{ route('inventory.template') }}" class="btn btn-outline-secondary btn-sm">
                                        Download Template
                                    </a>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                fixedHeader: true
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

            // Gunakan event delegation agar tetap berfungsi di semua pagination
            let currentInventoryName = 'qr-code';
            $(document).on('click', '.btn-show-image', function() {
                // Reset modal content
                $('#img-container').html('');
                $('#qr-container').html('');
                $('#download-qr-btn').hide();

                let img = $(this).data('img');
                let qrcode = $(this).data('qrcode');
                let name = $(this).data('name');
                currentInventoryName = name || 'qr-code';
                $('#imageModalLabel').text(name + ' - Image & QR Code');
                $('#img-container').html(img ?
                    `<img src="${img}" alt="Image" class="img-fluid mb-2 rounded" style="max-width:100%;">` :
                    '<span class="text-muted">No Project Image</span>');
                if (qrcode) {
                    $('#qr-container').html(
                        `<img id="qr-svg" src="${qrcode}" alt="QR Code" style="max-width:200px;">`);
                    $('#download-qr-btn').show();
                } else {
                    $('#qr-container').html('<span class="text-muted">No QR Code</span>');
                    $('#download-qr-btn').hide();
                }
            });

            // Download QR as PNG
            $('#download-qr-btn').on('click', function() {
                let qrImg = document.getElementById('qr-svg');
                if (!qrImg) return;
                html2canvas(qrImg, {
                    backgroundColor: null
                }).then(function(canvas) {
                    let link = document.createElement('a');
                    let filename = (currentInventoryName || 'qr-code').replace(/\s+/g, '-')
                        .toLowerCase() + '-qrcode.png';
                    link.download = filename;
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        });
    </script>
@endpush
