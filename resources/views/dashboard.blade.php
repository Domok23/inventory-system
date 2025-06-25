@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm rounded">
            <div class="card-body">
                <h3 class="card-title">Welcome, {{ ucwords($user->username) }}</h3>
                <p class="card-text">You are logged in as: <strong>{{ ucwords(str_replace('_', ' ', $user->role)) }}</strong>
                </p>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Inventory</h5>
                                <p class="card-text fs-4">{{ $inventoryCount }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Projects</h5>
                                <p class="card-text fs-4">{{ $projectCount }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pending Requests</h5>
                                <p class="card-text fs-4">{{ $pendingRequests }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    @if (in_array($user->role, ['super_admin', 'admin_logistic']))
                        <a href="{{ route('inventory.index') }}" class="btn btn-success mb-2">Go to Inventory</a>
                        <a href="{{ route('goods_out.index') }}" class="btn btn-primary mb-2">Go to Goods Out</a>
                    @endif

                    @if (in_array($user->role, ['super_admin', 'admin_mascot', 'admin_costume']))
                        <a href="{{ route('projects.index') }}" class="btn btn-warning mb-2">Go to Projects</a>
                    @endif

                    @if (in_array($user->role, ['super_admin', 'admin_mascot', 'admin_costume', 'admin_logistic']))
                        <a href="{{ route('material_requests.index') }}" class="btn btn-danger mb-2">Go to Material
                            Requests</a>
                        <a href="{{ route('goods_in.index') }}" class="btn btn-primary mb-2">Go to Goods In</a>
                    @endif

                    @if (in_array($user->role, ['super_admin', 'admin_finance']))
                        <a href="{{ route('costing.report') }}" class="btn btn-info mb-2">View Costing Reports</a>
                    @endif

                    @if (in_array($user->role, ['super_admin', 'admin_finance']))
                        <a href="{{ route('currencies.index') }}" class="btn btn-secondary mb-2">Manage Currencies</a>
                    @endif

                    @if ($user->role === 'super_admin')
                        <a href="{{ route('users.index') }}" class="btn btn-primary mb-2">Manage Users</a>
                    @endif
                </div>

                <!-- Super Admin Actions -->
                @if ($user->role === 'super_admin')
                    <div class="mt-4">
                        <h4>Super Admin Actions</h4>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary artisan-action" data-action="storage-link">
                                <i class="bi bi-link"></i> Create Storage Link
                            </button>
                            <button class="btn btn-outline-danger artisan-action" data-action="clear-cache">
                                <i class="bi bi-trash"></i> Clear Cache
                            </button>
                            <button class="btn btn-outline-warning artisan-action" data-action="config-clear">
                                <i class="bi bi-gear"></i> Clear Config
                            </button>
                            <button class="btn btn-outline-success artisan-action" data-action="config-cache">
                                <i class="bi bi-gear-fill"></i> Cache Config
                            </button>
                            <button class="btn btn-outline-info artisan-action" data-action="route-clear">
                                <i class="bi bi-signpost"></i> Clear Routes
                            </button>
                            <button class="btn btn-outline-secondary artisan-action" data-action="route-cache">
                                <i class="bi bi-signpost-2"></i> Cache Routes
                            </button>
                            <button class="btn btn-outline-dark artisan-action" data-action="view-cache">
                                <i class="bi bi-eye"></i> Clear Views
                            </button>
                            <button class="btn btn-outline-primary artisan-action" data-action="optimize">
                                <i class="bi bi-lightning"></i> Optimize
                            </button>
                            <button class="btn btn-outline-danger artisan-action" data-action="optimize-clear">
                                <i class="bi bi-lightning-fill"></i> Optimize Clear
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol Artisan Actions
            document.querySelectorAll('.artisan-action').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.dataset.action;

                    // Tampilkan loading SweetAlert
                    Swal.fire({
                        title: 'Processing...',
                        text: `Executing ${action}...`,
                        icon: 'info',
                        scrollbarPadding: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim AJAX request ke server
                    fetch(`/artisan/${action}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                });
            });
        });
    </script>
@endpush
