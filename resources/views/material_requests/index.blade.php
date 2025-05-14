@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('material_requests.create') }}" class="btn btn-success mb-3">+ Request Material</a>
    <a href="{{ route('material_requests.bulk_create') }}" class="btn btn-outline-secondary mb-3">
    + Bulk Request</a>

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
                <th>Status</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td>{{ $req->inventory->name }}</td>
                    <td>{{ $req->project->name }}</td>
                    <td>{{ $req->qty }} {{ $req->inventory->unit }}</td>
                    <td>{{ $req->requested_by }} ({{ ucfirst($req->department) }})</td>
                    <td>
                        @if(in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                            <form method="POST" action="{{ route('material_requests.update', $req->id) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $req->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="delivered" {{ $req->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </form>
                        @else
                            {{ ucfirst($req->status) }}
                        @endif
                    </td>
                    <td>{{ $req->remark }}</td>
                    <td>
                        <a href="{{ route('material_requests.edit', $req->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('material_requests.destroy', $req->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Delete</button>
                        </form>
                        @if($req->status === 'approved')
                            <a href="{{ route('goods_out.create', $req->id) }}" class="btn btn-sm btn-success">Goods Out</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
