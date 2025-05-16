@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Goods In Records</h3>
    <a href="{{ route('goods_in.create_independent') }}" class="btn btn-primary mb-3">+ Create Goods In</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
            </tr>
        </thead>
        <tbody>
            @foreach ($goodsIns as $goodsIn)
                <tr>
                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                    <td class="align-middle">{{ $goodsIn->goodsOut->inventory->name }}</td>
                    <td class="align-middle">{{ $goodsIn->quantity }} {{ $goodsIn->goodsOut->inventory->unit }}</td>
                    <td class="align-middle">{{ $goodsIn->goodsOut->project->name }}</td>
                    <td class="align-middle">{{ $goodsIn->returned_by }}</td>
                    <td class="align-middle">{{ $goodsIn->returned_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();

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
