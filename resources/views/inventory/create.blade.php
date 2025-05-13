@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Inventory</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Material Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
            @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <select id="unit-select" class="form-select" name="unit">
                <option value="">Select Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                @endforeach
                <option value="__new__">Add New Unit</option>
            </select>
            @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
            <input type="text" id="unit-input" class="form-control mt-2 d-none" name="new_unit" placeholder="Enter new unit">
        </div>

        <div class="mb-3">
            <label for="currency_id" class="form-label">Currency</label>
            <button type="button" class="btn btn-outline-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;" data-bs-toggle="modal" data-bs-target="#currencyModal">
                + Add Currency
            </button>
            <select name="currency_id" id="currency_id" class="form-select" required>
                @foreach($currencies as $currency)
                <option value="{{ $currency->id }}" {{ old('currency_id', $inventory->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                    {{ $currency->name }}
                </option>
                @endforeach
            </select>
            @error('currency_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location (Optional)</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
            @error('location') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Image (Optional)</label>
            <input type="file" class="form-control" id="img" name="img" accept="image/*" value="{{ old('img') }}">
            @error('img') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Inventory</button>
    </form>
    <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="currencyForm" method="POST" action="{{ route('currencies.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="currencyModalLabel">Add/Edit Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="currency_name" class="form-label">Currency Name</label>
                        <input type="text" id="currency_name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="currency_exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" id="currency_exchange_rate" name="exchange_rate" class="form-control" required>
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const unitSelect = document.getElementById('unit-select');
        const unitInput = document.getElementById('unit-input');

        unitSelect.addEventListener('change', function () {
            if (this.value === '__new__') {
                unitInput.classList.remove('d-none');
                unitInput.setAttribute('required', 'required');
            } else {
                unitInput.classList.add('d-none');
                unitInput.removeAttribute('required');
            }
        });
    });
</script>
@endpush
