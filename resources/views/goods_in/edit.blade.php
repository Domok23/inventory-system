@extends('layouts.app')
@section('content')
    <div class="container">
        <h3>Edit Goods In</h3>
        <form action="{{ route('goods_in.update', $goods_in->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Material</label>
                <select name="inventory_id" class="form-control select2" required>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                            {{ $goods_in->inventory_id == $inventory->id ? 'selected' : '' }}>
                            {{ $inventory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quantity</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" value="{{ $goods_in->quantity }}" required>
                    <span class="input-group-text unit-label">
                        {{ $goods_in->inventory ? $goods_in->inventory->unit : 'unit' }}
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <select name="project_id" class="form-control select2">
                    <option value="">No Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ $goods_in->project_id == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Returned At</span></label>
                <input type="datetime-local" name="returned_at" class="form-control"
                    value="{{ \Carbon\Carbon::parse($goods_in->returned_at)->format('Y-m-d\TH:i') }}" required>
                <small class="form-text text-muted">
                    Current: {{ \Carbon\Carbon::parse($goods_in->returned_at)->format('d-m-Y, H:i') }}
                </small>
            </div>
            <div class="mb-3">
                <label for="remark" class="form-label">Remark</label>
                <textarea name="remark" id="remark" class="form-control">{{ old('remark', $goodsIn->remark ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
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
