@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Inventory List</h2>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add Inventory</a>

        <!-- Button to Open Modal -->
        <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#importModal">
            Import Inventory via XLS
        </button>

        <!-- Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('inventory.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Inventory</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="xls_file" class="form-label">Upload XLS File</label>
                                <input type="file" name="xls_file" id="xls_file" class="form-control" required
                                    accept=".xls,.xlsx">
                            </div>
                            <p class="text-muted">Template kolom: <code>name, quantity, unit, price, location
                                    (opsional)</code></p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
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
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Location</th>
                    <th>QR Code</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $inventory)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $inventory->name }}</td>
                        <td class="align-middle">{{ $inventory->quantity }} {{ $inventory->unit }}</td>
                        <td class="align-middle">
                            @if ($inventory->currency)
                                {{ $inventory->currency->name }}
                            @endif
                            {{ number_format($inventory->price, 2, ',', '.') }}
                        </td>
                        <td class="align-middle">{{ $inventory->location }}</td>
                        <td class="align-middle">
                            @if ($inventory->qrcode_path)
                                <img src="{{ asset('storage/' . $inventory->qrcode_path) }}" alt="QR Code" width="80">
                            @endif
                        </td>
                        <td class="align-middle">
                            @if ($inventory->img)
                                <img src="{{ asset('storage/' . $inventory->img) }}" alt="Image" width="100">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('inventory.scan', ['id' => $inventory->id]) }}" class="btn btn-sm btn-info">Detail</a>
                            <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST"
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

