@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Create Goods In</h3>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('goods_in.store_independent') }}">
            @csrf
            <div class="mb-3">
                <label>Material</label>
                <select name="inventory_id" class="form-select select2" required>
                    <option value="">Select Material</option>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label>Unit</label>
                <input type="text" class="form-control" value="" id="unit" disabled>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <select name="project_id" class="form-select select2" required>
                    <option value="">Select Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
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
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select an option',
                allowClear: true
            });

            // Update unit dynamically when material is selected
            $('select[name="inventory_id"]').on('change', function() {
                const selectedUnit = $(this).find(':selected').data('unit');
                $('#unit').val(selectedUnit || '');
            });
        });
    </script>
@endpush
