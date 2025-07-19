@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Card Wrapper -->
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-lg-0 flex-shrink-0" style="font-size:1.3rem;"><i class="bi bi-box-seam"></i>
                        Inventory
                        List</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-lg-auto d-flex flex-wrap gap-2">
                        @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
                                <i class="bi bi-plus-circle"></i> Create Inventory
                            </a>
                            <button type="button" class="btn btn-success btn-sm flex-shrink-0" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="bi bi-filetype-xls"></i> Import
                            </button>
                        @endif
                        <a href="{{ route('inventory.export', request()->query()) }}"
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
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {!! session('warning') !!}
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
                        <div class="col-lg-2">
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
                            <div class="col-lg-2">
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
                        <div class="col-lg-2">
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
                        <div class="col-lg-2">
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
                        <div class="col-lg-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary" id="filter-btn">
                                <span class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                    aria-hidden="true"></span>
                                Filter
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-striped table-hover table-bordered" id="datatable">
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
                                <td>{{ rtrim(rtrim(number_format($inventory->quantity, 2, '.', ''), '0'), '.') }}
                                    {{ $inventory->unit }}</td>
                                @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic', 'admin_finance']))
                                    <td>
                                        {{ number_format($inventory->price ?? 0, 2, ',', '.') }}
                                        {{ $inventory->currency ? $inventory->currency->name : '' }}
                                    </td>
                                @endif
                                <td>{{ $inventory->supplier ?? '-' }}</td>
                                <td>{{ $inventory->location ?? '-' }}</td>
                                <td>{!! $inventory->remark ?? '-' !!}</td>
                                <td>
                                    <div class="d-flex flex-nowrap gap-1">
                                        <a href="{{ route('inventory.detail', ['id' => $inventory->id]) }}"
                                            class="btn btn-sm btn-success" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="More Detail"><i
                                                class="bi bi-info-circle"></i></a>
                                        <button type="button" class="btn btn-sm btn-secondary btn-show-image"
                                            title="View Image & QR Code" data-bs-toggle="modal" data-bs-target="#imageModal"
                                            data-img="{{ $inventory->img ? asset('storage/' . $inventory->img) : '' }}"
                                            data-qrcode="{{ $inventory->qr_code }}" data-name="{{ $inventory->name }}">
                                            <i class="bi bi-file-earmark-image"></i>
                                        </button>
                                        @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                            <a href="{{ route('inventory.edit', $inventory->id) }}"
                                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Edit"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <form action="{{ route('inventory.destroy', $inventory->id) }}"
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

                <!-- Modal Show Image -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div id="img-container" class="mb-3"></div>
                                <div id="qr-code-container" class="mb-3"></div>
                                <a id="download-qr-code" class="btn btn-outline-primary btn-sm" href="#"
                                    download="qr-code.png" style="display: none;">
                                    <i class="bi bi-download"></i> Download QR Code
                                </a>
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
                                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-filetype-xls"
                                            style="color: rgb(0, 129, 65);"></i> Import Inventory</h5>
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
                                        You can Import Inventories via Excel file. Please ensure the file is formatted
                                        correctly.
                                        <br>
                                        <strong>Note:</strong> The file must be in XLS or XLSX format.
                                        <br>
                                        <strong>Column Template:</strong>
                                        <br>
                                        <code>Name, Category, Quantity, Unit, Price, Currency, Supplier, Location</code>
                                    </p>
                                    <a href="{{ route('inventory.template') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-download"></i> Download Template
                                    </a>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="import-btn">
                                        <span class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                            aria-hidden="true"></span>
                                        Import
                                    </button>
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
            // Initialize DataTable
            $('#datatable').DataTable({
                responsive: true,
                fixedHeader: true,
                select: true,
                stateSave: true,
                createdRow: function(row, data, dataIndex) {
                    // Ambil nilai quantity dari kolom ke-4 (index 3)
                    const quantity = parseFloat(data[3]); // Pastikan kolom quantity berada di index 3
                    if (quantity === 0) {
                        // Tambahkan gaya untuk quantity = 0
                        $('td', row).eq(3).css({
                            'color': '#ef4444', // Warna merah (Bootstrap text-danger)
                            // 'background-color': '#f8d7da',
                            'font-weight': 'bold'
                        });
                    } else if (quantity < 3) {
                        // Tambahkan gaya untuk quantity < 3
                        $('td', row).eq(3).css({
                            'color': '#f97316',
                            // 'background-color': '#fff3cd',
                            'font-weight': 'bold'
                        });
                    }
                }
            });

            // --- Spinner Filter Button ---
            const filterBtn = document.getElementById('filter-btn');
            const filterSpinner = filterBtn ? filterBtn.querySelector('.spinner-border') : null;
            const filterForm = filterBtn ? filterBtn.closest('form') : null;
            const filterBtnHtml = filterBtn ? filterBtn.innerHTML : '';

            if (filterForm && filterBtn && filterSpinner) {
                filterForm.addEventListener('submit', function() {
                    filterBtn.disabled = true;
                    filterSpinner.classList.remove('d-none');
                    filterBtn.childNodes[2].textContent = ' Filtering...';
                });
            }

            // --- Spinner Import Button in Modal ---
            const importBtn = document.getElementById('import-btn');
            const importSpinner = importBtn ? importBtn.querySelector('.spinner-border') : null;
            const importForm = importBtn ? importBtn.closest('form') : null;
            const importBtnHtml = importBtn ? importBtn.innerHTML : '';

            if (importForm && importBtn && importSpinner) {
                importForm.addEventListener('submit', function() {
                    importBtn.disabled = true;
                    importSpinner.classList.remove('d-none');
                    importBtn.childNodes[2].textContent = ' Importing...';
                });
            }

            // Reset tombol Spinner Import saat modal dibuka ulang
            $('#importModal').on('shown.bs.modal', function() {
                if (importBtn) {
                    importBtn.disabled = false;
                    importBtn.innerHTML = importBtnHtml;
                }
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
            $(document).on('click', '.btn-show-image', function() {
                // Reset modal content
                $('#img-container').html('');
                $('#qr-code-container').html('');
                $('#download-qr-code').hide(); // Sembunyikan tombol download

                let img = $(this).data('img');
                let qrcode = $(this).data('qrcode'); // Ambil QR Code dari atribut data
                let name = $(this).data('name');

                // Debugging untuk memeriksa nilai atribut
                console.log('Image URL:', img);
                console.log('QR Code:', qrcode);
                console.log('Name:', name);

                $('#imageModalLabel').html(
                    `<i class="bi bi-image" style="margin-right: 5px; color: cornflowerblue;"></i> ${name}`
                );

                // Tampilkan gambar jika ada
                $('#img-container').html(img ?
                    `<a href="${img}" data-fancybox="gallery" data-caption="${name}">
                        <img src="${img}" alt="Image" class="img-fluid img-hover rounded" style="max-width:100%;">
                    </a>` :
                    '<span class="text-muted">No Image</span>'
                );

                // Tampilkan QR Code jika ada
                $('#qr-code-container').html(qrcode ?
                    `<div>
                        <img src="${qrcode}" alt="QR Code" class="img-fluid" style="max-width:100%;">
                    </div>` :
                    '<span class="text-muted">No QR Code</span>'
                );

                if (qrcode) {
                    $('#download-qr-code').attr('href', qrcode).show();
                }
            });

            // Initialize Bootstrap Tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();
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

        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
