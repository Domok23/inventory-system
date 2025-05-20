@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Edit Inventory</h2>

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

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Material Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $inventory->name) }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
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

            <!-- Quantity -->
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                    value="{{ old('quantity', $inventory->quantity) }}" required>
                @error('quantity')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Unit -->
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
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

            <!-- Currency -->
            <div class="mb-3">
                <label for="currency_id" class="form-label">Currency</label>
                <button type="button" class="btn btn-outline-primary"
                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                    data-bs-toggle="modal" data-bs-target="#currencyModal">
                    + Add Currency
                </button>
                <select name="currency_id" id="currency_id" class="form-select" required>
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

            <!-- Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Unit Price</label>
                <input type="number" class="form-control" step="0.01" id="price" name="price"
                    value="{{ old('price', $inventory->price) }}">
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Location -->
            <div class="mb-3">
                <label for="location" class="form-label">Location (optional)</label>
                <input type="text" class="form-control" id="location" name="location"
                    value="{{ old('location', $inventory->location) }}">
                @error('location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Image -->
            <div class="mb-3">
                <label for="img" class="form-label">Upload Image (optional)</label>
                <input class="form-control" type="file" id="img" name="img" accept="image/*"
                    onchange="previewImage(event)">
                <img id="img-preview"
                    src="{{ isset($inventory) && $inventory->img ? asset('storage/' . $inventory->img) : '' }}"
                    alt="Image Preview" class="mt-2"
                    style="max-width: 200px; display: {{ isset($inventory) && $inventory->img ? 'block' : 'none' }};">
                @error('img')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <!-- Display current image if exists -->
                @if ($inventory->img)
                    <div class="mt-2">
                        <strong>Current Image:</strong><br>
                        <img src="{{ asset('storage/' . $inventory->img) }}" alt="Material Image" width="200">
                    </div>
                @endif
            </div>

            <!-- QR Code -->
            @if ($inventory->qrcode_path)
                <div class="mb-3">
                    <label class="form-label">QR Code</label><br>
                    <img src="{{ asset('storage/' . $inventory->qrcode_path) }}" alt="QR Code" width="150">
                </div>
            @endif

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Update Inventory</button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Back</a>
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
                                <label for="category_name" class="form-label">Category Name</label>
                                <input type="text" id="category_name" name="name" class="form-control" required>
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
                                <label for="currency_name" class="form-label">Currency Name</label>
                                <input type="text" id="currency_name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="currency_exchange_rate" class="form-label">Exchange Rate</label>
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
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
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

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
