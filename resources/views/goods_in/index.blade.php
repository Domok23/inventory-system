@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center mb-3 gap-2">
            <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Goods In Records</h2>
            <a href="{{ route('goods_in.create_independent') }}" class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2"
                data-bs-toggle="modal" data-bs-target="#currencyModal">+ Create Goods
                In</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <table class="table table-hover table-bordered" id="datatable">
            <thead>
                <tr>
                    <th style="width: 20px;" class="text-center align-middle">#</th>
                    <th>Material</th>
                    <th>Quantity Returned</th>
                    <th>Project</th>
                    <th>Returned By</th>
                    <th>Returned At</th>
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
                                <span class="text-danger">-</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ ucfirst($goodsIn->returned_by) }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($goodsIn->returned_at)->format('d-m-Y, H:i') }}
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('goods_in.edit', $goodsIn->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('goods_in.destroy', $goodsIn->id) }}" method="POST"
                                style="display:inline;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true
            });

            // SweetAlert for delete confirmation
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
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
