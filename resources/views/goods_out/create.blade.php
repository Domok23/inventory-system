@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Goods Out for {{ $materialRequest->project->name }}
                    ({{ $materialRequest->inventory->name }})</h2>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('goods_out.store') }}">
                    @csrf
                    <input type="hidden" name="material_request_id" value="{{ $materialRequest->id }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Material <span class="text-danger">*</span></label>
                            <select name="inventory_id" class="form-select select2" required>
                                @foreach ($inventories as $inventory)
                                    <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                                        {{ old('inventory_id', $materialRequest->inventory_id) == $inventory->id ? 'selected' : '' }}>
                                        {{ $inventory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Project</label>
                            <input type="text" class="form-control" value="{{ $materialRequest->project->name }}"
                                disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Quantity Requested</label>
                            <input type="text" class="form-control"
                                value="{{ $materialRequest->qty }} {{ $materialRequest->inventory->unit }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Quantity to Goods Out <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control"
                                    value="{{ old('quantity', $materialRequest->qty) }}" step="any" required>
                                <span class="input-group-text unit-label">
                                    {{ $materialRequest->inventory ? $materialRequest->inventory->unit : 'unit' }}
                                </span>
                            </div>
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Requested By</label>
                            <input type="text" class="form-control"
                                value="{{ ucwords($materialRequest->requested_by) }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Department</label>
                            <input type="text" class="form-control" value="{{ ucfirst($materialRequest->department) }}"
                                disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" rows="2">{{ old('remark') }}</textarea>
                            @error('remark')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <a href="{{ route('material_requests.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Submit</button>
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
