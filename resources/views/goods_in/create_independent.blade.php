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
                        <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}">
                            {{ $inventory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quantity</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" step="any" required>
                    <span class="input-group-text unit-label">unit</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <select name="project_id" class="form-select select2">
                    <option value="">No Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Returned/In At</label>
                <input type="datetime-local" name="returned_at" class="form-control"
                    value="{{ old('returned_at', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="mb-3">
                <label>Returned/In By</label>
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
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Update unit label dynamically when material is selected
            $('select[name="inventory_id"]').on('change', function() {
                const selectedUnit = $(this).find(':selected').data('unit');
                $('.unit-label').text(selectedUnit || 'unit');
            });
            $('select[name="inventory_id"]').trigger('change');
        });
    </script>
@endpush
