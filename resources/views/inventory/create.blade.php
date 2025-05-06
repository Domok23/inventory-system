@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Inventory</h2>

    <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Material Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location (Optional)</label>
            <input type="text" class="form-control" id="location" name="location">
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Image (Optional)</label>
            <input type="file" class="form-control" id="img" name="img">
        </div>

        <button type="submit" class="btn btn-primary">Create Inventory</button>
    </form>
</div>
@endsection
