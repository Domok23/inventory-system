@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Goods Out</h3>
        <a href="{{ route('goods_out.create_independent') }}" class="btn btn-primary mb-3">+ Create Goods Out</a>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Quantity</th>
                    <th>For Project</th>
                    <th>Requested By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($goodsOuts as $goodsOut)
                    <tr>
                        <td>{{ $goodsOut->inventory->name }}</td>
                        <td>{{ $goodsOut->quantity }} {{ $goodsOut->inventory->unit }}</td>
                        <td>{{ $goodsOut->project->name }}</td>
                        <td>{{ $goodsOut->requested_by }}</td>
                        <td>
                            <a href="{{ route('goods_out.create', $goodsOut->material_request_id) }}"
                                class="btn btn-sm btn-primary">Process</a>
                            <a href="{{ route('goods_out.edit', $goodsOut->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('goods_out.destroy', $goodsOut->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this Goods Out?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
