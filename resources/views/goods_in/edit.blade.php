@extends('layouts.app')
@section('content')
    <div class="container">
        <h3>Edit Goods In</h3>
        <form action="{{ route('goods_in.update', $goods_in->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Material</label>
                <select name="inventory_id" class="form-control" required>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}"
                            {{ $goods_in->inventory_id == $inventory->id ? 'selected' : '' }}>
                            {{ $inventory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <select name="project_id" class="form-control" required>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ $goods_in->project_id == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $goods_in->quantity }}" required>
            </div>
            <div class="mb-3">
                <label>Returned At</span></label>
                <input type="datetime-local" name="returned_at" class="form-control"
                    value="{{ \Carbon\Carbon::parse($goods_in->returned_at)->format('Y-m-d\TH:i') }}" required>
                <small class="form-text text-muted">
                    Current: {{ \Carbon\Carbon::parse($goods_in->returned_at)->format('d-m-Y, H:i') }}
                </small>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
