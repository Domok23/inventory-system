@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Create Inventory</h2>
                <hr>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Material Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                            data-bs-target="#addCategoryModal">
                            + Add Category
                        </button>
                        <select name="category_id" id="category_id" class="form-select select2" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $inventory->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" id="quantity" name="quantity"
                                value="{{ old('quantity') }}" min="0" required>
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addUnitModal"
                                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;">
                                + Add Unit
                            </button>
                            <select id="unit-select" class="form-select select2" name="unit" required>
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->name }}"
                                        {{ old('unit') == $unit->name ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6 mb-3">
                            <label for="price" class="form-label">Unit Price</label>
                            <input type="number" step="any" class="form-control" id="price" name="price"
                                value="{{ old('price', $inventory->price ?? '') }}">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="currency_id" class="form-label">Currency</label>
                            <button type="button" class="btn btn-outline-primary"
                                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                                data-bs-toggle="modal" data-bs-target="#currencyModal">
                                + Add Currency
                            </button>
                            <select name="currency_id" id="currency_id" class="form-select select2">
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}"
                                        {{ old('currency_id', $inventory->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="supplier_id" class="form-label">Supplier (Optional)</label>
                            <button type="button" class="btn btn-outline-primary"
                                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                                data-bs-toggle="modal" data-bs-target="#addSupplierModal">+ Add Supplier</button>
                            <select name="supplier_id" id="supplier_id" class="form-select select2">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $inventory->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="location_id" class="form-label">Location (Optional)</label>
                            <button type="button" class="btn btn-outline-primary"
                                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                                data-bs-toggle="modal" data-bs-target="#addLocationModal">+ Add Location</button>
                            <select name="location_id" id="location_id" class="form-select select2">
                                <option value="">Select Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('location_id', $inventory->location_id ?? '') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="remark" class="form-label">Remark (Optional)</label>
                            <textarea class="form-control" id="remark" name="remark" rows="2">{{ old('remark') }}</textarea>
                            @error('remark')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <label for="img" class="form-label">Upload Image (optional)</label>
                        <input class="form-control" type="file" id="img" name="img" accept="image/*"
                            onchange="previewImage(event)">
                        <a id="img-preview-link" href="#" data-fancybox="gallery" style="display: none;">
                            <img id="img-preview" src="#" alt="Image Preview" class="mt-2 rounded"
                                style="max-width: 200px;">
                        </a>
                        @error('img')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="inventory-submit-btn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                aria-hidden="true"></span>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Unit Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="unitForm" method="POST" action="{{ route('units.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUnitModalLabel">Add New Unit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Unit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Currency Modal -->
    <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="currencyForm" method="POST" action="{{ route('currencies.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="currencyModalLabel">Add New Currency</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="currency_name" class="form-label">Currency Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="currency_name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="currency_exchange_rate" class="form-label">Exchange Rate <span
                                    class="text-danger">*</span></label>
                            <input type="number" id="currency_exchange_rate" name="exchange_rate" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Add Supplier -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="supplierForm" method="POST" action="{{ route('suppliers.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supplierModalLabel">Add Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="supplier_name" class="form-label">Supplier Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="supplier_name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Add Location -->
    <div class="modal fade" id="addLocationModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="locationForm" method="POST" action="{{ route('locations.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="locationModalLabel">Add Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="location_name" class="form-label">Location Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="location_name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.375rem + 2px);
            /* Tinggi elemen form Bootstrap */
            padding: 0.375rem 0.75rem;
            /* Padding elemen form Bootstrap */
            font-size: 1rem;
            /* Ukuran font Bootstrap */
            line-height: 1.5;
            /* Line height Bootstrap */
            border: 1px solid #ced4da;
            /* Border Bootstrap */
            border-radius: 0.375rem;
            /* Border radius Bootstrap */
        }

        .select2-selection__rendered {
            line-height: 1.5;
            /* Line height Bootstrap */
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(2.375rem + 2px);
            /* Tinggi elemen form Bootstrap */
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Fancybox untuk gambar preview
            Fancybox.bind("[data-fancybox='gallery']", {
                Toolbar: {
                    display: [
                        "zoom", // Tombol zoom
                        "download", // Tombol download
                        "close" // Tombol close
                    ],
                },
                Thumbs: false, // Nonaktifkan thumbnail jika tidak diperlukan
                Image: {
                    zoom: true, // Aktifkan fitur zoom
                },
                Hash: false,
            });

            // Spinner dan disable tombol untuk form utama Inventory
            const form = document.querySelector('form[action="{{ route('inventory.store') }}"]');
            const submitBtn = document.getElementById('inventory-submit-btn');
            const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;

            if (form && submitBtn && spinner) {
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');
                    submitBtn.childNodes[2].textContent = ' Saving...';
                });
            }
        });

        $(document).ready(function() {
            // Inisialisasi Select2 untuk dropdown Kategori
            $('#category_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Category',
                allowClear: true
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Submit form kategori via AJAX
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(category) {
                        // Tambahkan ke select2 dan pilih otomatis
                        let newOption = new Option(category.name, category.id, true, true);
                        $('#category_id').append(newOption).trigger('change');
                        $('#addCategoryModal').modal('hide');
                        form[0].reset();
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message ||
                            'Failed to add category. Please try again.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Inisialisasi Select2 untuk dropdown Unit
            $('#unit-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Unit',
                allowClear: true
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Submit form unit via AJAX
            $('#unitForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(unit) {
                        // Tambahkan ke select2 dan pilih otomatis
                        let newOption = new Option(unit.name, unit.name, true, true);
                        $('#unit-select').append(newOption).val(unit.name).trigger('change');
                        $('#addUnitModal').modal('hide');
                        form[0].reset();
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message ||
                            'Failed to add unit. Please try again.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Inisialisasi Select2 untuk dropdown Currency
            $('#currency_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Currency',
                allowClear: true
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Submit form currency via AJAX
            $('#currencyForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        // Tambahkan ke select2 dan pilih otomatis
                        let newOption = new Option(res.name, res.id, true, true);
                        $('#currency_id').append(newOption).val(res.id).trigger('change');
                        $('#currencyModal').modal('hide');
                        form[0].reset();
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message ||
                            'Failed to add currency. Please try again.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Inisialisasi Select2 untuk dropdown Supplier
            $('#supplier_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Supplier',
                allowClear: true
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Submit form supplier via AJAX
            $('#supplierForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(supplier) {
                        let newOption = new Option(supplier.name, supplier.id, true, true);
                        $('#supplier_id').append(newOption).val(supplier.id).trigger('change');
                        $('#addSupplierModal').modal('hide');
                        form[0].reset();
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add supplier.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Inisialisasi Select2 untuk dropdown Location
            $('#location_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Location',
                allowClear: true
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Submit form location via AJAX
            $('#locationForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(location) {
                        let newOption = new Option(location.name, location.id, true, true);
                        $('#location_id').append(newOption).val(location.id).trigger('change');
                        $('#addLocationModal').modal('hide');
                        form[0].reset();
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add location.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });
        });

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('img-preview');
            const previewLink = document.getElementById('img-preview-link');
            const maxSize = 2 * 1024 * 1024; // 2 MB

            if (input.files && input.files[0]) {
                // Validasi ukuran file
                if (input.files[0].size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large',
                        text: 'Maximum file size is 2 MB.',
                    });
                    input.value = '';
                    if (preview) preview.src = '';
                    if (previewLink) previewLink.href = '#';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) preview.src = e.target.result;
                    if (previewLink) {
                        previewLink.href = e.target.result;
                        preview.style.display = 'block';
                        previewLink.style.display = 'block';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                if (preview) preview.src = '';
                if (previewLink) previewLink.href = '#';
                if (preview) preview.style.display = 'none';
                if (previewLink) previewLink.style.display = 'none';
            }
        }
    </script>
@endpush
