@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Create Goods In</h2>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('goods_in.store_independent') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label>Material <span class="text-danger">*</span></label>
                            <select name="inventory_id" class="form-select select2" required>
                                <option value="">Select Material</option>
                                @foreach ($inventories as $inventory)
                                    <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}">
                                        {{ $inventory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" step="any" required>
                                <span class="input-group-text unit-label">unit</span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Project</label>
                            <select name="project_id" class="form-select select2">
                                <option value="" class="text-muted">No Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Returned/In At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="returned_at" class="form-control"
                                value="{{ old('returned_at', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Returned/In By</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control" rows="3">{{ old('remark') }}</textarea>
                        </div>
                    </div>
                    <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success" id="goodsin-submit-btn">
                        <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                        Submit
                    </button>
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

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('goods_in.store_independent') }}"]');
            const submitBtn = document.getElementById('goodsin-submit-btn');
            const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;

            if (form && submitBtn && spinner) {
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');
                    submitBtn.childNodes[2].textContent = ' Submitting...';
                });
            }

            // Jika pakai AJAX, aktifkan kembali tombol di error handler:
            // submitBtn.disabled = false;
            // spinner.classList.add('d-none');
            // submitBtn.childNodes[2].textContent = ' Submit';
        });
    </script>
@endpush
