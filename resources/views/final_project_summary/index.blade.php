@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h2 class="mb-4 text-primary">Final Project Summary</h2>
            <form method="GET" class="row g-2 align-items-end mb-3">
                <div class="col-md-4">
                    <label class="form-label mb-1">Search Project</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Project name...">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Filter Department</label>
                    <select name="department" class="form-select select2" data-placeholder="All Departments">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ ucfirst($dept) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('final_project_summary.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle rounded shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th style="width:45%;">Project Name</th>
                            <th style="width:25%;">Department</th>
                            <th style="width:20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="timing-rows">
                        @include('final_project_summary._project_table', ['projects' => $projects])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 2.375rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border-radius: 0.375rem;
    }
    .select2-selection__rendered {
        line-height: 2.2rem;
    }
    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 2.375rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });

        // Live search
        $('input[name="search"], select[name="department"]').on('input change', function() {
            let search = $('input[name="search"]').val();
            let department = $('select[name="department"]').val();
            $.ajax({
                url: "{{ route('final_project_summary.ajax_search') }}",
                data: {search: search, department: department},
                success: function(res) {
                    $('#timing-rows').html(res.html);
                }
            });
        });
    });
</script>
@endpush