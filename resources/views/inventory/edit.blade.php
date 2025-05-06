@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Inventory</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Material Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $inventory->name) }}" required>
        </div>

        <!-- Quantity -->
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" required>
        </div>

        <!-- Unit -->
        <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit', $inventory->unit) }}" required>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="price" class="form-label">Price (optional)</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $inventory->price) }}">
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Location (optional)</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $inventory->location) }}">
        </div>

        <!-- Image -->
        <div class="mb-3">
            <label for="img" class="form-label">Upload Image (optional)</label>
            <input class="form-control" type="file" id="img" name="img">
            @if ($inventory->img)
                <div class="mt-2">
                    <strong>Current Image:</strong><br>
                    <img src="{{ asset('storage/' . $inventory->img) }}" alt="Material Image" width="150">
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
</div>
@endsection
