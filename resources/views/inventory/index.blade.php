@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Card Wrapper -->
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-3 gap-2">
                    <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Inventory List</h2>
                    <a href="{{ route('inventory.create') }}" class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2">
                        + Add Inventory
                    </a>
                    <div class="flex-grow-1"></div>
                    <button type="button" class="btn btn-success btn-sm flex-shrink-0 ms-2 d-none d-md-inline"
                        data-bs-toggle="modal" data-bs-target="#importModal">
                        Import Inventory via XLS
                    </button>
                </div>
                <div class="mb-3 d-block d-md-none">
                    <button type="button" class="btn btn-success btn-sm w-100" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        Import Inventory via XLS
                    </button>
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
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventories as $inventory)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $inventory->name }}</td>
                                <td class="align-middle">
                                    {{ $inventory->category ? $inventory->category->name : '-' }}
                                </td>
                                <td class="align-middle">{{ $inventory->quantity }} {{ $inventory->unit }}</td>
                                <td class="align-middle">
                                    @if ($inventory->currency)
                                        {{ $inventory->currency->name }}
                                    @endif
                                    {{ number_format($inventory->price, 2, ',', '.') }}
                                </td>
                                <td class="align-middle">{{ $inventory->location }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('inventory.edit', $inventory->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('inventory.detail', ['id' => $inventory->id]) }}"
                                            class="btn btn-sm btn-success">Detail</a>
                                        <button type="button" class="btn btn-sm btn-info btn-show-image"
                                            data-bs-toggle="modal" data-bs-target="#imageModal"
                                            data-img="{{ $inventory->img ? asset('storage/' . $inventory->img) : '' }}"
                                            data-qrcode="{{ $inventory->qrcode_path ? asset('storage/' . $inventory->qrcode_path) : '' }}"
                                            data-name="{{ $inventory->name }}">
                                            Show
                                        </button>
                                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
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

    <!-- Modal Show Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Inventory Image & QR Code </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="img-container" class="mb-3"></div>
                    <div id="qr-container" class="mb-3"></div>
                    <button id="download-qr-btn" class="btn btn-outline-primary btn-sm mb-3" style="display:none;">Download
                        QR as PNG</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                fixedHeader: true
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

            // Gunakan event delegation agar tetap berfungsi di semua pagination
            let currentInventoryName = 'qr-code';
            $(document).on('click', '.btn-show-image', function() {
                // Reset modal content
                $('#img-container').html('');
                $('#qr-container').html('');
                $('#download-qr-btn').hide();

                let img = $(this).data('img');
                let qrcode = $(this).data('qrcode');
                let name = $(this).data('name');
                currentInventoryName = name || 'qr-code';
                $('#imageModalLabel').text(name + ' - Image & QR Code');
                $('#img-container').html(img ?
                    `<img src="${img}" alt="Image" class="img-fluid mb-2 rounded" style="max-width:100%;">` :
                    '<span class="text-muted">No Project Image</span>');
                if (qrcode) {
                    $('#qr-container').html(
                        `<img id="qr-svg" src="${qrcode}" alt="QR Code" style="max-width:200px;">`);
                    $('#download-qr-btn').show();
                } else {
                    $('#qr-container').html('<span class="text-muted">No QR Code</span>');
                    $('#download-qr-btn').hide();
                }
            });

            // Download QR as PNG
            $('#download-qr-btn').on('click', function() {
                let qrImg = document.getElementById('qr-svg');
                if (!qrImg) return;
                html2canvas(qrImg, {
                    backgroundColor: null
                }).then(function(canvas) {
                    let link = document.createElement('a');
                    let filename = (currentInventoryName || 'qr-code').replace(/\s+/g, '-')
                        .toLowerCase() + '-qrcode.png';
                    link.download = filename;
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        });
    </script>
@endpush
