@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h3 class="mb-3 flex-shrink-0 text-primary">
                    Final Project Summary</h3>
                <form method="GET" class="row g-2 align-items-end mb-3">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Search Project</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Project name...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">Filter Department</label>
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
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('final_project_summary.index') }}"
                            class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover align-middle rounded shadow-sm" id="projects-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width:45%;">Project Name</th>
                                <th style="width:25%;">Department</th>
                                <th style="width:20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="timing-rows">
                            @include('final_project_summary.project_table', ['projects' => $projects])
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
            font-size: 0.875rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background: white;
            color: #495057;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper .row .col-md-6 {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 1rem;
            }

            .dataTables_wrapper .row .col-md-6:last-child {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let dt;
        let dtConfig = {
            responsive: true,
            searching: false, // Disable DataTables built-in search
            paging: true,
            info: true,
            ordering: true,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            language: {
                emptyTable: "No projects found",
                zeroRecords: "No projects found"
            },
            columnDefs: [{
                targets: 2, // Action column
                orderable: false,
                searchable: false
            }],
            order: [
                [0, 'asc']
            ], // Default sort by project name
            drawCallback: function(settings) {
                // Callback setelah table di-draw ulang
                console.log('DataTables redrawn with', settings.fnRecordsTotal(), 'records');
            }
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
            dt = $('#projects-table').DataTable(dtConfig);

            // Live search dengan AJAX
            $('input[name="search"], select[name="department"]').on('input change', function() {
                let search = $('input[name="search"]').val();
                let department = $('select[name="department"]').val();

                $.ajax({
                    url: "{{ route('final_project_summary.ajax_search') }}",
                    method: 'GET',
                    data: {
                        search: search,
                        department: department
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
                            dt = $('#projects-table').DataTable(dtConfig);

                        } catch (error) {
                            console.error('Error reinitializing DataTables:', error);

                            // Fallback: reload page jika ada error
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        alert('Error loading data. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
