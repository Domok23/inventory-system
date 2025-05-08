@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory List</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add Inventory</a>

    <!-- Button to Open Modal -->
    <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#importModal">
        Import Inventory via XLS
    </button>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
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
                        <label for="xls_file" class="form-label">Upload XLS File</label>
                        <input type="file" name="xls_file" id="xls_file" class="form-control" required accept=".xls,.xlsx">
                    </div>
                    <p class="text-muted">Template kolom: <code>name, quantity, unit, price, location (opsional)</code></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Location</th>
                <th>QR Code</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->name }}</td>
                    <td>{{ $inventory->quantity }} {{ $inventory->unit }}</td>
                    <td>
                        @if($inventory->currency)
                            {{ $inventory->currency->name }}
                        @endif
                        {{ number_format($inventory->price, 2, ',', '.') }}
                    </td>
                    <td>{{ $inventory->location }}</td>
                    <td>@if ($inventory->qrcode_path)
                        <img src="{{ asset('storage/' . $inventory->qrcode_path) }}" alt="QR Code" width="80">
                    @endif</td>
                    <td>
                        @if($inventory->img)
                            <img src="{{ asset('storage/' . $inventory->img) }}" alt="Image" width="100">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
