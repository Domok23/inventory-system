@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Edit Goods Out</h3>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form method="POST" action="{{ route('goods_out.update', $goodsOut->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Material</label>
                <select name="inventory_id" class="form-select select2" required>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                            {{ $inventory->id == $goodsOut->inventory_id ? 'selected' : '' }}>
                            {{ $inventory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quantity</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" value="{{ $goodsOut->quantity }}"
                        step="0.01" required>
                    <span class="input-group-text unit-label">{{ $goodsOut->inventory->unit }}</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Project</label>
                <select name="project_id" class="form-select select2" required>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ $project->id == $goodsOut->project_id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Requested By</label>
                <select name="user_id" class="form-select select2" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" data-department="{{ $user->department }}"
                            {{ $user->username == $goodsOut->requested_by ? 'selected' : '' }}>
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Department</label>
                <input type="text" class="form-control" id="department" value="{{ $goodsOut->department }}" disabled>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('goods_out.index') }}" class="btn btn-secondary">Cancel</a>
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

            // Set initial department value
            const initialDepartment = $('select[name="user_id"]').find(':selected').data('department');
            $('#department').val(initialDepartment || '');

            // Update department dynamically when user is selected
            $('select[name="user_id"]').on('change', function() {
                const selectedDepartment = $(this).find(':selected').data('department');
                $('#department').val(selectedDepartment || '');
            });
        });
    </script>
@endpush
