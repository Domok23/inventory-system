@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-3 gap-2 overflow-hidden">
                    <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Material Requests</h2>
                    <a href="{{ route('material_requests.create') }}"
                        class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2">
                        + Request Material
                    </a>
                    <div class="flex-grow-1"></div>
                    <a href="{{ route('material_requests.bulk_create') }}"
                        class="btn btn-success btn-sm flex-shrink-0 ms-2 d-none d-md-inline">
                        + Bulk Request
                    </a>
                    <button id="bulk-goods-out-btn" class="btn btn-primary btn-sm flex-shrink-0 d-none d-md-inline">Bulk
                        Goods
                        Out</button>
                </div>
                <!-- Bulk Request button for mobile, appears below the title bar -->
                <div class="mb-3 d-block d-md-none">
                    <a href="{{ route('material_requests.bulk_create') }}" class="btn btn-success btn-sm w-100">
                        + Bulk Request
                    </a>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Table -->
                <table class="table table-bordered table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Project</th>
                            <th>Material</th>
                            <th>Requested Qty</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Remark</th>
                            <th>Requested At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                            <tr id="row-{{ $req->id }}">
                                <td>
                                    @if ($req->status === 'approved')
                                        <input type="checkbox" class="select-row" value="{{ $req->id }}">
                                    @endif
                                </td>
                                <td class="align-middle">{{ $req->project->name ?? '-' }}</td>
                                <td class="align-middle">{{ $req->inventory->name ?? '-' }}</td>
                                <td class="align-middle">{{ $req->qty }} {{ $req->inventory->unit ?? '-' }}</td>
                                <td class="align-middle">{{ ucfirst($req->requested_by) }}
                                    ({{ ucfirst($req->department) }})
                                </td>
                                <td class="align-middle">
                                    @if (in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                                        <form method="POST" action="{{ route('material_requests.update', $req->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="approved"
                                                    {{ $req->status === 'approved' ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="delivered"
                                                    {{ $req->status === 'delivered' ? 'selected' : '' }}>
                                                    Delivered</option>
                                            </select>
                                        </form>
                                    @else
                                        {{ ucfirst($req->status) }}
                                    @endif
                                </td>
                                <td class="align-middle">{{ $req->remark }}</td>
                                <td class="align-middle">{{ $req->created_at->format('d-m-Y, H:i') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('material_requests.edit', $req->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        @if ($req->status === 'approved')
                                            <a href="{{ route('goods_out.create', $req->id) }}"
                                                class="btn btn-sm btn-success">Goods
                                                Out</a>
                                        @endif
                                        <form action="{{ route('material_requests.destroy', $req->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                desytroy: true,
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    }, // Kolom checkbox tidak dapat diurutkan
                ],
            });

            // SweetAlert for delete confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#select-all').on('change', function() {
                $('.select-row').prop('checked', $(this).prop('checked'));
            });

            $('#bulk-goods-out-btn').on('click', function() {
                const selectedIds = $('.select-row:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire('Error', 'Please select at least one material request.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to process bulk goods out.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('goods_out.bulk') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                selected_ids: selectedIds,
                            },
                            success: function(response) {
                                Swal.fire('Success',
                                        'Bulk Goods Out processed successfully.',
                                        'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Error',
                                    'An error occurred while processing.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
