@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row align-items-md-center mb-md-3">
                    <!-- Header -->
                    <h2 class="mb-md-0 flex-shrink-0" style="font-size:1.5rem;"><i class="bi bi-arrow-down-circle"></i> Goods In
                        Records</h2>
                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-md-auto flex-wrap">
                        <a href="{{ route('goods_in.create_independent') }}" class="btn btn-primary btn-sm flex-shrink-0">
                            + New Goods In
                        </a>
                    </div>
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
                <table class="table table-hover table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Material</th>
                            <th>Qty Returned/In</th>
                            <th>Project</th>
                            <th>Returned/In By</th>
                            <th>Returned/In At</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goodsIns as $goodsIn)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    @if ($goodsIn->goodsOut && $goodsIn->goodsOut->inventory)
                                        {{ $goodsIn->goodsOut->inventory->name }}
                                    @elseif($goodsIn->inventory)
                                        {{ $goodsIn->inventory->name }}
                                    @else
                                        <span class="text-danger">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if ($goodsIn->goodsOut && $goodsIn->goodsOut->inventory)
                                        {{ $goodsIn->quantity }} {{ $goodsIn->goodsOut->inventory->unit }}
                                    @elseif($goodsIn->inventory)
                                        {{ $goodsIn->quantity }} {{ $goodsIn->inventory->unit }}
                                    @else
                                        {{ $goodsIn->quantity }}
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if ($goodsIn->goodsOut && $goodsIn->goodsOut->project)
                                        {{ $goodsIn->goodsOut->project->name }}
                                    @elseif($goodsIn->project)
                                        {{ $goodsIn->project->name }}
                                    @else
                                        <span class="text-secondary">No Project (Restock/Supplier)</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ ucfirst($goodsIn->returned_by) }}</td>
                                <td class="align-middle">
                                    {{ \Carbon\Carbon::parse($goodsIn->returned_at)->format('d-m-Y, H:i') }}
                                </td>
                                <td class="align-middle">
                                    @if ($goodsIn->remark)
                                        {{ $goodsIn->remark }}
                                    @else
                                        <span class="text-danger">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('goods_in.edit', $goodsIn->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        @if (!$goodsIn->goods_out_id)
                                            <form action="{{ route('goods_in.destroy', $goodsIn->id) }}" method="POST"
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
        });
    </script>
@endpush
