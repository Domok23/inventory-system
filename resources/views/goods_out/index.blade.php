@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Goods Out</h3>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Material</th>
                <th>Project</th>
                <th>Quantity</th>
                <th>Requested By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goodsOuts as $goodsOut)
                <tr>
                    <td>{{ $goodsOut->inventory->name }}</td>
                    <td>{{ $goodsOut->project->name }}</td>
                    <td>{{ $goodsOut->quantity }}</td>
                    <td>{{ $goodsOut->requested_by }}</td>
                    <td>
                        <a href="{{ route('goods_out.create', $goodsOut->material_request_id) }}" class="btn btn-sm btn-primary">Process</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
