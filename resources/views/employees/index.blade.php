@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-lg-0 flex-shrink-0" style="font-size:1.3rem;"><i class="fas fa-users"></i> Employees
                        Data
                    </h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-lg-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Employee
                        </a>
                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-hover align-middle" id="employees-table">
                    <thead class="table-light">
                        <tr>
                            <th>Employee Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->department ? ucfirst($employee->department->name) : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $employee->status_badge['color'] }}">
                                        {{ $employee->status_badge['text'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="{{ route('employees.timing', $employee) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-clock"></i> Timing
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <span class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                                    aria-hidden="true"></span>
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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

        #employees-table_wrapper .dataTables_paginate .paginate_button.current,
        #employees-table_wrapper .dataTables_paginate .paginate_button.current:focus,
        #employees-table_wrapper .dataTables_paginate .paginate_button.current:active {
            background: #8116ed !important;
            border-color: #8116ed !important;
            color: #fff !important;
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

        /* Search input styling */
        .dataTables_filter input {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-left: 0.5rem;
        }

        /* Button group styling */
        .btn-group .btn {
            margin: 0 2px;
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
    <script>
        $(document).ready(function() {
            // Konfigurasi DataTables
            const dtConfig = {
                searching: true,
                paging: true,
                info: true,
                ordering: true,
                lengthChange: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    search: "Search employees:",
                    info: "Total: _TOTAL_ employees",
                    infoEmpty: "No employees available",
                    infoFiltered: "(filtered from _MAX_ total employees)",
                    emptyTable: "No employees found",
                    zeroRecords: "No employees match your search criteria"
                },
                columnDefs: [{
                    targets: 4, // Action column (index 4)
                    orderable: false,
                    searchable: false
                }, ],
                order: [
                    [0, 'asc']
                ], // Default sort by employee name
                responsive: true,
                scrollX: true // Enable horizontal scrolling for mobile
            };

            // Inisialisasi DataTables
            $('#employees-table').DataTable(dtConfig);

            // konfirmasi delete dengan SweetAlert
            $(document).on('click', '.btn-danger', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const employeeName = $(this).closest('tr').find('td:first').text();

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete employee "${employeeName}"? This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Disable tombol dan tampilkan spinner
                        const btn = form.find('button[type="submit"]');
                        const spinner = btn.find('.spinner-border');
                        btn.prop('disabled', true);
                        spinner.removeClass('d-none');

                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
