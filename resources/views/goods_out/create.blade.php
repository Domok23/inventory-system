@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Goods Out</h3>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form method="POST" action="{{ route('goods_out.store') }}">
            @csrf
            <input type="hidden" name="material_request_id" value="{{ $materialRequest->id }}">
            <div class="mb-3">
                <label>Material</label>
                <select name="inventory_id" class="form-select select2" required>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                            {{ $inventory->id == $materialRequest->inventory_id ? 'selected' : '' }}>
                            {{ $inventory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <input type="text" class="form-control" value="{{ $materialRequest->project->name }}" disabled>
            </div>
            <div class="mb-3">
                <label>Quantity Requested</label>
                <input type="text" class="form-control"
                    value="{{ $materialRequest->qty }} {{ $materialRequest->inventory->unit }}" disabled>
            </div>
            <div class="mb-3">
                <label>Quantity to Goods Out</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" value="{{ $materialRequest->qty }}"
                        step="0.01" required>
                    <span class="input-group-text unit-label">
                        {{ $materialRequest->inventory ? $materialRequest->inventory->unit : 'unit' }}
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <label>Requested By</label>
                <input type="text" class="form-control" value="{{ $materialRequest->requested_by }}" disabled>
            </div>
            <div class="mb-3">
                <label>Department</label>
                <input type="text" class="form-control" value="{{ ucfirst($materialRequest->department) }}" disabled>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="{{ route('material_requests.index') }}" class="btn btn-secondary">Cancel</a>
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
                placeholder: 'Select Material',
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
            // Trigger saat halaman load jika sudah ada value terpilih
            $('select[name="inventory_id"]').trigger('change');
        });
    </script>
@endpush
