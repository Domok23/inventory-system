@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                <h2 class="mb-0 text-primary">Employee Timing Data</h2>
                <a href="{{ route('timings.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Input Timing
                </a>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="GET" class="row g-2 align-items-end mb-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari step, remarks...">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Filter Project</label>
                    <select name="project_id" class="form-select select2" data-placeholder="All Projects">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Department</label>
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
                    <label class="form-label mb-1">Employee</label>
                    <select name="employee_id" class="form-select select2" data-placeholder="All Employees">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('timings.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle rounded shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Department</th>
                            <th>Step</th>
                            <th>Parts</th>
                            <th>Employee</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="timing-rows">
                        @include('timings._timing_table', ['timings' => $timings])
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
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        // AJAX realtime search & filter
        $('input[name="search"], select[name="project_id"], select[name="department"], select[name="employee_id"]').on('input change', function() {
            let search = $('input[name="search"]').val();
            let project_id = $('select[name="project_id"]').val();
            let department = $('select[name="department"]').val();
            let employee_id = $('select[name="employee_id"]').val();
            $.ajax({
                url: "{{ route('timings.ajax_search') }}",
                data: {
                    search: search,
                    project_id: project_id,
                    department: department,
                    employee_id: employee_id
                },
                success: function(res) {
                    $('#timing-rows').html(res.html);
                }
            });
        });
    });
</script>
@endpush