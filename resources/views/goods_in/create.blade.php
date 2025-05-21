@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Process Goods In</h3>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('goods_in.store') }}">
            @csrf
            <input type="hidden" name="goods_out_id" value="{{ $goodsOut->id }}">
            <div class="mb-3">
                <label>Material</label>
                <input type="text" class="form-control" value="{{ $goodsOut->inventory->name }}" disabled>
            </div>
            <div class="mb-3">
                <label>Proceeded Quantity</label>
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $goodsOut->quantity }}" disabled>
                    <span class="input-group-text unit-label">{{ $goodsOut->inventory->unit }}</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Quantity Returned (If Any)</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" step="0.01"
                        max="{{ $goodsOut->quantity }}" value="{{ old('quantity', $goodsOut->quantity) }}" required>
                    <span class="input-group-text unit-label">{{ $goodsOut->inventory->unit }}</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <input type="text" class="form-control" value="{{ $goodsOut->project->name }}" disabled>
            </div>
            <div class="mb-3">
                <label>Returned At</label>
                <input type="datetime-local" name="returned_at" class="form-control"
                    value="{{ old('returned_at', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="mb-3">
                <label>Returned By</label>
                <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled>
            </div>
            <div class="mb-3">
                <label>Remark</label>
                <textarea name="remark" class="form-control" rows="3">{{ old('remark') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
