@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Edit Goods In</h2>
                <hr>
                <form action="{{ route('goods_in.update', $goods_in->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Material</label>
                            <select name="inventory_id" class="form-control select2" placeholder="Select Material" required>
                                @foreach ($inventories as $inventory)
                                    <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                                        {{ $goods_in->inventory_id == $inventory->id ? 'selected' : '' }}>
                                        {{ $inventory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Quantity</label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="{{ $goods_in->quantity }}"
                                    required>
                                <span class="input-group-text unit-label">
                                    {{ $goods_in->inventory ? $goods_in->inventory->unit : 'unit' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Project</label>
                            <select name="project_id" class="form-control select2">
                                <option value="" {{ empty($goods_in->project_id) ? 'selected' : '' }} class="text-muted">No Project
                                </option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $goods_in->project_id == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Returned At</span></label>
                            <input type="datetime-local" name="returned_at" class="form-control"
                                value="{{ \Carbon\Carbon::parse($goods_in->returned_at)->format('Y-m-d\TH:i') }}" required>
                            <small class="form-text text-muted">
                                Current: {{ \Carbon\Carbon::parse($goods_in->returned_at)->format('m/d/Y, H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Returned By</label>
                            <input type="text" class="form-control" value="{{ $goods_in->returned_by }}" disabled>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control">{{ old('remark', $goodsIn->remark ?? '') }}</textarea>
                        </div>
                    </div>
                    <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
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
