@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Edit Goods Out</h2>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('goods_out.update', $goodsOut->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3">
                            <label>Material <span class="text-danger">*</span></label>
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
                            <label>Quantity <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="{{ $goodsOut->quantity }}"
                                    step="any" required>
                                <span class="input-group-text unit-label">{{ $goodsOut->inventory->unit }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Project <span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select select2" required>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $goodsOut->project_id ? 'selected' : '' }}>
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
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" data-department="{{ $user->department }}"
                                        {{ $user->username == $goodsOut->requested_by ? 'selected' : '' }}>
                                        {{ $user->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Department</label>
                            <input type="text" class="form-control" id="department" value="{{ $goodsOut->department }}"
                                disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" rows="2">{{ old('remark', $goodsOut->remark ?? '') }}</textarea>
                        </div>
                    </div>

                    <a href="{{ route('goods_out.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update</button>
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
                placeholder: 'Select an option',
                allowClear: true
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
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
