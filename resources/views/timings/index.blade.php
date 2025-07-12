@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                    <h3 class="mb-0 text-primary">Employee Timing Data</h3>
                    <a href="{{ route('timings.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Input Timing
                    </a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" class="row g-2 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari step, remarks...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">Filter Project</label>
                        <select name="project_id" class="form-select select2" data-placeholder="All Projects">
                            <option value="">All Projects</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Department</label>
                        <select name="department" class="form-select select2" data-placeholder="All Departments">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
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
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
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
                <table class="table table-bordered table-hover align-middle rounded shadow-sm" id="timing-table">
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
                        @include('timings.timing_table', ['timings' => $timings])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Select2 Styling */
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

        /* DataTables Styling */
        .dataTables_wrapper {
            margin-top: 1rem;
        }

        /* Spacing antara tabel dan elemen bawah */
        .dataTables_wrapper .bottom {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }

        /* Grid layout untuk elemen bawah */
        .dataTables_wrapper .row {
            margin: 0;
            align-items: center;
        }

        .dataTables_wrapper .row .col-md-6 {
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
        }

        /* Left side: Length dan Info */
        .dataTables_wrapper .row .col-md-6:first-child {
            justify-content: flex-start;
        }

        /* Right side: Pagination */
        .dataTables_wrapper .row .col-md-6:last-child {
            justify-content: flex-end;
        }

        /* Individual element styling */
        .dataTables_length {
            margin-right: 1rem;
            margin-bottom: 0 !important;
        }

        .dataTables_info {
            margin-bottom: 0 !important;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .dataTables_paginate {
            margin-bottom: 0 !important;
        }

        /* Pagination buttons styling */
        .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            margin: 0 0.125rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background: white;
            color: #495057;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .dataTables_paginate .paginate_button.current {
            background: #007bff;
            border-color: #007bff;
            color: white;
        }

        .dataTables_paginate .paginate_button.disabled {
            color: #6c757d;
            background: #f8f9fa;
            border-color: #dee2e6;
            cursor: not-allowed;
        }

        /* Length select styling */
        .dataTables_length select {
            padding: 0.25rem 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            margin: 0 0.5rem;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .dataTables_wrapper .row .col-md-6 {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 1rem;
            }

            .dataTables_wrapper .row .col-md-6:last-child {
                align-items: flex-end;
            }

            .dataTables_length {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }

        .no-data-row td {
            background-color: #f8f9fa;
            font-style: italic;
            color: #6c757d;
        }

        .no-data-row td i {
            margin-right: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let dt;
        let dtConfig = {
            responsive: true,
            searching: false,
            paging: true,
            info: true,
            ordering: true,
            lengthChange: true,
            pageLength: 25,
            dom: '<"top">rt<"bottom"<"row"<"col-md-6"li><"col-md-6 text-end"p>>>',
            language: {
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                lengthMenu: "Show _MENU_ entries per page",
                emptyTable: "No timing data found",
                zeroRecords: "No timing data found"
            },
            columnDefs: [{
                targets: '_all',
                defaultContent: '-'
            }]
        };

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            // Inisialisasi DataTables
            dt = $('#timing-table').DataTable(dtConfig);

            // AJAX search & filter
            $('input[name="search"], select[name="project_id"], select[name="department"], select[name="employee_id"]')
                .on('input change', function() {
                    let search = $('input[name="search"]').val();
                    let project_id = $('select[name="project_id"]').val();
                    let department = $('select[name="department"]').val();
                    let employee_id = $('select[name="employee_id"]').val();

                    $.ajax({
                        url: "{{ route('timings.ajax_search') }}",
                        method: 'POST',
                        data: {
                            search: search,
                            project_id: project_id,
                            department: department,
                            employee_id: employee_id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            try {
                                // Destroy DataTables dengan error handling
                                if (dt && typeof dt.destroy === 'function') {
                                    dt.destroy();
                                }

                                // Update content
                                $('#timing-rows').html(res.html);

                                // Reinit DataTables dengan config yang sama
                                dt = $('#timing-table').DataTable(dtConfig);

                            } catch (error) {
                                console.error('Error reinitializing DataTables:', error);

                                // Fallback: reload page jika ada error
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            console.error('AJAX Error:', xhr);
                            console.error('Status:', xhr.status);
                            console.error('Response:', xhr.responseText);

                            // Show user-friendly error
                            alert('Error loading data. Please try again.');
                        }
                    });
                });
        });
    </script>
@endpush
