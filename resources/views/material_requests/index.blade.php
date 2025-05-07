@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('material_requests.create') }}" class="btn btn-success mb-3">+ Request Material</a>
    <a href="{{ route('material_requests.bulk_create') }}" class="btn btn-outline-secondary mb-3">
    + Bulk Request</a>

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
                    <td>{{ ucfirst($req->status) }}</td>
                    <td>{{ $req->remark }}</td> 
                    <td>
                        <a href="{{ route('material_requests.edit', $req->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('material_requests.destroy', $req->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
