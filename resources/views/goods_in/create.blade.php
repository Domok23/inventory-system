@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Process Goods In</h2>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('goods_in.store') }}">
                    @csrf
                    <input type="hidden" name="goods_out_id" value="{{ $goodsOut->id }}">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label>Material</label>
                            <input type="text" class="form-control" value="{{ $goodsOut->inventory->name }}" disabled>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Proceeded Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control" value="{{ $goodsOut->quantity }}" disabled>
                                <span class="input-group-text unit-label">{{ $goodsOut->inventory->unit }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Quantity Returned <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" step="any"
                                    max="{{ $goodsOut->quantity }}" value="{{ old('quantity', $goodsOut->quantity) }}"
                                    required>
                                <span class="input-group-text unit-label">{{ $goodsOut->inventory->unit }}</span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Project</label>
                            <input type="text" class="form-control" value="{{ $goodsOut->project->name }}" disabled>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Returned At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="returned_at" class="form-control"
                                value="{{ old('returned_at', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Returned By</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control" rows="3">{{ old('remark') }}</textarea>
                        </div>
                    </div>
                    <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
