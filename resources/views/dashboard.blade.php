@extends('layouts.app')
@php
    // Ambil waktu server lalu konversi ke timestamp JS
    $serverTime = \Carbon\Carbon::now();
@endphp
@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm rounded">
            <div class="card-body position-relative">
                <div class="dashboard-clock-container position-absolute top-0 end-0 p-3" style="z-index:10;">
                    <div class="text-dark rounded px-3 py-2 text-end" style="min-width: 160px;">
                        <div id="realtime-clock" style="font-size:1.1rem; font-weight:600; letter-spacing:1px;">00:00</div>
                        <div id="realtime-date" style="font-size:0.95rem; opacity:0.8;">Loading date...</div>
                    </div>
                </div>
                <h3 class="card-title">Welcome, {{ ucwords($user->username) }}</h3>
                <p class="card-text">You are logged in as:
                    <strong>{{ ucwords(str_replace('_', ' ', $user->role)) }}</strong>
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

                    @if (in_array($user->role, ['super_admin', 'admin_mascot', 'admin_costume', 'admin_animatronic', 'general']))
                        <a href="{{ route('projects.index') }}" class="btn btn-warning mb-2">Go to Projects</a>
                    @endif

                    @if (in_array($user->role, [
                            'super_admin',
                            'admin_mascot',
                            'admin_costume',
                            'admin_logistic',
                            'admin_animatronic',
                            'general',
                        ]))
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
@push('styles')
    <style>
        @media (max-width: 539.98px) {
            .dashboard-clock-container {
                position: absolute !important;
                top: 0.5rem;
                right: 0.5rem;
                left: auto;
                z-index: 10;
                min-width: 0 !important;
                width: auto;
                padding: 0.25rem 0.5rem !important;
                text-align: right;
            }

            .dashboard-clock-container .text-dark {
                font-size: 0.95rem;
                padding: 0.25rem 0.5rem;
                min-width: 0;
            }

            #realtime-clock {
                font-size: 1rem !important;
            }

            #realtime-date {
                font-size: 0.85rem !important;
            }

            .card-body.position-relative {
                padding-top: 3.2rem !important;
            }
        }

        @media (min-width: 540px) {
            .dashboard-clock-container {
                position: absolute !important;
                top: 0;
                right: 0;
                z-index: 10;
                min-width: 160px;
                text-align: right;
                padding: 1rem;
            }
        }

        @media (max-width: 767.98px) {}
    </style>
@endpush
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

        // Ambil waktu server dari PHP (format ISO 8601)
        const serverTime = new Date("{{ $serverTime->format('Y-m-d\TH:i:sP') }}");
        let clientTime = new Date();
        // Hitung selisih waktu client-server (ms)
        const timeOffset = serverTime.getTime() - clientTime.getTime();

        function updateClock() {
            // Gunakan waktu client + offset server
            const now = new Date(Date.now() + timeOffset);
            // Format waktu: HH:MM (tanpa detik)
            const pad = n => n.toString().padStart(2, '0');
            const time = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
            // Format tanggal: Monday, 4 July 2025
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            const day = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const fullDate = `${day}, ${date} ${month} ${year}`;

            document.getElementById('realtime-clock').textContent = time;
            document.getElementById('realtime-date').textContent = fullDate;
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateClock();
            setInterval(updateClock, 10000); // update tiap 10 detik cukup
        });
    </script>
@endpush
