@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-3 gap-2">
                    <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;"><i class="bi bi-arrow-up-circle"></i> Goods Out
                        Records
                    </h2>
                    <a href="{{ route('goods_out.create_independent') }}"
                        class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2">+
                        Create Goods Out</a>
                    <button id="bulk-goods-in-btn" class="btn btn-primary btn-sm">Bulk Goods In</button>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Material</th>
                            <th>Qty</th>
                            <th>Remaining Qty</th>
                            <th>For Project</th>
                            <th>Requested By</th>
                            <th>Remark</th>
                            <th>Proceed At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goodsOuts as $goodsOut)
                            <tr>
                                <td>
                                    @if ($goodsOut->quantity > 0)
                                        <input type="checkbox" class="select-row" value="{{ $goodsOut->id }}">
                                    @endif
                                </td>
                                <td class="align-middle">{{ $goodsOut->inventory->name ?? '-' }}</td>
                                <td class="align-middle">{{ $goodsOut->quantity }} {{ $goodsOut->inventory->unit ?? '-' }}
                                </td>
                                <td class="align-middle">{{ $goodsOut->remaining_quantity }}
                                    {{ $goodsOut->inventory->unit ?? '-' }}
                                </td>
                                <td class="align-middle">{{ $goodsOut->project->name ?? '-' }}</td>
                                <td class="align-middle">{{ ucfirst($goodsOut->requested_by) }}
                                    ({{ ucfirst($goodsOut->department) }})
                                </td>
                                <td class="align-middle child">
                                    @if ($goodsOut->remark)
                                        {{ $goodsOut->remark }}
                                    @else
                                        <span class="text-danger">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $goodsOut->created_at->format('d-m-Y, H:i') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                        @if ($goodsOut->quantity > 0)
                                            <a href="{{ route('goods_in.create', ['goods_out_id' => $goodsOut->id]) }}"
                                                class="btn btn-sm btn-success">
                                                Goods In
                                            </a>
                                        @endif
                                        @if ($goodsOut->material_request_id && $goodsOut->materialRequest && $goodsOut->materialRequest->qty > 0)
                                            <a href="{{ route('goods_out.create', $goodsOut->material_request_id) }}"
                                                class="btn btn-sm btn-primary">
                                                Process
                                            </a>
                                        @endif
                                        <a href="{{ route('goods_out.edit', $goodsOut->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        @if ($goodsOut->goodsIns->isEmpty())
                                            {{-- Cek apakah tidak ada Goods In terkait --}}
                                            <form action="{{ route('goods_out.destroy', $goodsOut->id) }}" method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
                                        @endif
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
                responsive: true
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

            $('#bulk-goods-in-btn').on('click', function() {
                const selectedIds = $('.select-row:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire('Error', 'Please select at least one goods out.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to process bulk goods in.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('goods_in.bulk') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                selected_ids: selectedIds,
                            },
                            success: function(response) {
                                Swal.fire('Success',
                                        'Bulk Goods In processed successfully.',
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
