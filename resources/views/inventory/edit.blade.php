@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Edit Inventory</h2>
                <hr>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
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

                <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Name -->
                        <div class="col-lg-12 mb-3">
                            <label for="name" class="form-label">Material Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $inventory->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                                data-bs-target="#addCategoryModal">
                                + Add Category
                            </button>
                            <select name="category_id" id="category_id" class="form-select select2" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quantity -->
                        <div class="col-lg-6 mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" name="quantity"
                                value="{{ old('quantity', $inventory->quantity) }}" step="any" required>
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div class="col-lg-6 mb-3">
                            <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                            <select id="unit-select" class="form-select select2" name="unit" required>
                                <option value="">Select Unit</option>
                                <option value="__new__">Add New Unit?</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->name }}"
                                        {{ old('unit', $inventory->unit) == $unit->name ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" id="unit-input" class="form-control mt-2 d-none" name="new_unit"
                                placeholder="Enter new unit">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Price -->
                        <div class="col-lg-6 mb-3">
                            <label for="price" class="form-label">Unit Price</label>
                            <input type="number" class="form-control" step="any" id="price" name="price"
                                value="{{ old('price', $inventory->price) }}">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Currency -->
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
                                        {{ old('currency_id', $inventory->currency_id) == $currency->id ? 'selected' : '' }}>
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
                        <!-- Supplier -->
                        <div class="col-lg-6 mb-3">
                            <label for="supplier" class="form-label">Supplier (Optional)</label>
                            <input type="text" class="form-control" id="supplier" name="supplier"
                                value="{{ old('supplier', $inventory->supplier) }}">
                            @error('supplier')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Location -->
                        <div class="col-lg-6 mb-3">
                            <label for="location" class="form-label">Location (optional)</label>
                            <input type="text" class="form-control" id="location" name="location"
                                value="{{ old('location', $inventory->location) }}">
                            @error('location')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="remark" class="form-label">Remark (Optional)</label>
                            <textarea class="form-control" id="remark" name="remark" rows="2">{{ old('remark', strip_tags($inventory->remark)) }}</textarea>
                            @error('remark')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Image -->
                        <div class="col-lg-6 mb-3">
                            <label for="img" class="form-label">Upload Image (optional)</label>
                            <input class="form-control" type="file" id="img" name="img" accept="image/*"
                                onchange="previewImage(event)">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Image Preview -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Image Preview</label><br>
                            <div id="img-preview-container">
                                @if (isset($inventory) && $inventory->img)
                                    <a id="img-preview-link" href="{{ asset('storage/' . $inventory->img) }}"
                                        data-fancybox="gallery">
                                        <img id="img-preview" src="{{ asset('storage/' . $inventory->img) }}"
                                            alt="Image Preview" class="rounded" style="max-width: 200px;">
                                    </a>
                                @else
                                    <span id="no-image-text" class="text-muted">No Image</span>
                                @endif
                            </div>
                            @error('img')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- QR Code -->
                        @if (isset($inventory) && $inventory->qr_code)
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">QR Code</label><br>
                                <div>
                                    <img src="{{ $inventory->qr_code }}" alt="QR Code" style="max-width: 150px;">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="col-lg-12">
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

                <!-- Add Category Modal -->
                <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label">Category Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="category_name" name="name" class="form-control"
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
                <!-- Add Currency Modal -->
                <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="currencyForm" method="POST" action="{{ route('currencies.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="currencyModalLabel">Add Currency</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="currency_name" class="form-label">Currency Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="currency_name" name="name" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="currency_exchange_rate" class="form-label">Exchange Rate <span
                                                class="text-danger">*</span></label>
                                        <input type="number" id="currency_exchange_rate" name="exchange_rate"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
            const unitSelect = document.getElementById('unit-select');
            const unitInput = document.getElementById('unit-input');

            unitSelect.addEventListener('change', function() {
                if (this.value === '__new__') {
                    unitInput.classList.remove('d-none');
                    unitInput.setAttribute('required', 'required');
                } else {
                    unitInput.classList.add('d-none');
                    unitInput.removeAttribute('required');
                    unitInput.value = ''; // Reset nilai input teks
                }
            });
        });

        // Inisialisasi Select2 untuk dropdown Unit
        document.addEventListener('DOMContentLoaded', function() {
            $('#unit-select').select2({
                placeholder: 'Select Unit',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5'
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Tampilkan input teks jika "Add New Unit" dipilih
            const unitInput = document.getElementById('unit-input');

            $('#unit-select').on('change', function() {
                if (this.value === '__new__') {
                    unitInput.classList.remove('d-none');
                    unitInput.setAttribute('required', 'required');
                } else {
                    unitInput.classList.add('d-none');
                    unitInput.removeAttribute('required');
                    unitInput.value = ''; // Reset nilai input teks
                }
            });
        });

        // Inisialisasi Select2 untuk dropdown Category
        $(document).ready(function() {
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
                        alert('Failed to add category: ' + xhr.responseJSON.message);
                    }
                });
            });
        });

        // Inisialisasi Select2 untuk dropdown Currency
        $(document).ready(function() {
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
        });

        // Quick Add Currency AJAX
        $(document).ready(function() {
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
                    success: function(currency) {
                        // Tambahkan ke select2 dan pilih otomatis
                        let newOption = new Option(currency.name, currency.id, true, true);
                        $('#currency_id').append(newOption).val(currency.id).trigger('change');
                        $('#currencyModal').modal('hide');
                        form[0].reset();
                    },
                    error: function(xhr) {
                        alert('Failed to add currency: ' + (xhr.responseJSON?.message || ''));
                    }
                });
            });
        });

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('img-preview');
            const previewLink = document.getElementById('img-preview-link');
            const noImageText = document.getElementById('no-image-text');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result; // Set src untuk gambar preview
                    previewLink.href = e.target.result; // Set href untuk Fancybox
                    preview.style.display = 'block';
                    previewLink.style.display = 'block';
                    noImageText.style.display = 'none'; // Sembunyikan teks "No Image"
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                previewLink.href = '#';
                preview.style.display = 'none';
                previewLink.style.display = 'none';
                noImageText.style.display = 'block'; // Tampilkan teks "No Image"
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
@endpush
