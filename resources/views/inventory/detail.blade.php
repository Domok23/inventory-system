@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Inventory Details</h3>
            </div>
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
                <a href="" class="btn btn-primary my-1">Goods In</a>
                <a href="" class="btn btn-success my-1">Goods Out</a>
                <a href="" class="btn btn-warning my-1">View Material Usage</a>
                <br>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary my-2">Back to Inventory</a>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection
