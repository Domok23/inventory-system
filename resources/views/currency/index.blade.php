@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-3 gap-2">
                    <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Currency List</h2>
                    <button type="button" class="btn btn-outline-primary btn-sm flex-shrink-0 ms-2" data-bs-toggle="modal"
                        data-bs-target="#currencyModal">
                        + New Currency
                    </button>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table table-hover table-bordered table-striped" id="datatable">
                    <thead class="align-middle">
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Exchange Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($currencies as $currency)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ number_format($currency->exchange_rate, 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-primary edit-currency-btn"
                                            data-id="{{ $currency->id }}" data-name="{{ $currency->name }}"
                                            data-exchange-rate="{{ $currency->exchange_rate }}" data-bs-toggle="modal"
                                            data-bs-target="#currencyModal">
                                            Edit
                                        </button>
                                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST"
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

    <!-- Modal -->
    <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="currencyForm" method="POST" action="{{ route('currencies.store') }}">
                @csrf
                <input type="hidden" id="currency_id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="currencyModalLabel">Add Currency</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="currency_name" class="form-label">Currency Name <span class="text-danger">*</span></label>
                            <input type="text" id="currency_name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="currency_exchange_rate" class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                            <input type="number" id="currency_exchange_rate" name="exchange_rate" class="form-control"
                                step="any" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-currency-btn');
            const currencyForm = document.getElementById('currencyForm');
            const currencyModalLabel = document.getElementById('currencyModalLabel');
            const currencyIdInput = document.getElementById('currency_id');
            const currencyNameInput = document.getElementById('currency_name');
            const currencyExchangeRateInput = document.getElementById('currency_exchange_rate');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const exchangeRate = this.getAttribute('data-exchange-rate');

                    // Set form action for editing
                    if (id) {
                        currencyForm.action = `/currencies/${id}`;
                        currencyForm.method = 'POST';
                        currencyForm.insertAdjacentHTML('beforeend',
                            '<input type="hidden" name="_method" value="PUT">');
                    } else {
                        currencyForm.action = '{{ route('currencies.store') }}';
                        currencyForm.method = 'POST';
                    }

                    // Set modal title
                    currencyModalLabel.textContent = id ? 'Edit Currency' : 'Add Currency';

                    // Fill form inputs
                    currencyIdInput.value = id || '';
                    currencyNameInput.value = name || '';
                    currencyExchangeRateInput.value = exchangeRate || '';
                });
            });

            // Reset modal when closed
            const currencyModal = document.getElementById('currencyModal');
            currencyModal.addEventListener('hidden.bs.modal', function() {
                currencyForm.reset();
                currencyForm.action = '{{ route('currencies.store') }}';
                currencyModalLabel.textContent = 'Add/Edit Currency';
                const methodInput = currencyForm.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();
            });
        });
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
