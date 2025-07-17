@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Create Goods Out</h2>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('goods_out.store_independent') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label>Material <span class="text-danger">*</span></label>
                            <select name="inventory_id" class="form-select select2" required>
                                <option value="">Select an option</option>
                                @foreach ($inventories as $inventory)
                                    <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                                        {{ old('inventory_id') == $inventory->id ? 'selected' : '' }}>
                                        {{ $inventory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" step="any"
                                    value="{{ old('quantity') }}" required>
                                <span class="input-group-text unit-label">{{ old('unit', 'unit') }}</span>
                            </div>
                            <input type="hidden" name="unit" id="unit-hidden" value="{{ old('unit') }}">
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Project</label>
                            <select name="project_id" class="form-select select2">
                                <option value="" class="text-muted">No Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label>Requested By <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select select2" required>
                                <option value="">Select an option</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        data-department="{{ $user->department ? $user->department->name : '' }}">
                                        {{ $user->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Department</label>
                            <input type="text" class="form-control" id="department" value="" disabled>
                            <input type="hidden" name="department" id="department-hidden" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" rows="2">{{ old('remark') }}</textarea>
                            @error('remark')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <a href="{{ route('goods_out.index') }}" class="btn btn-secondary">Cancel</a>
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
                $('#unit-hidden').val(selectedUnit || 'unit'); // Update hidden input
            });

            // Update department dynamically when user is selected
            $('select[name="user_id"]').on('change', function() {
                const selectedDepartment = $(this).find(':selected').data('department');
                $('#department').val(selectedDepartment || '');
                $('#department-hidden').val(selectedDepartment || '');
            });

            // Trigger change event on page load to restore old values
            $('select[name="inventory_id"]').trigger('change');
            $('select[name="user_id"]').trigger('change');
        });
    </script>
@endpush
