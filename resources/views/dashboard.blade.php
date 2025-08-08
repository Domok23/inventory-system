@extends('layouts.app')

@php
    $serverTime = \Carbon\Carbon::now();
@endphp

@section('content')
    <div class="container-fluid py-4">
        <!-- Welcome Header with Clock -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg bg-gradient-primary text-white position-relative overflow-hidden">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="ms-2">
                                        <h2 class="mb-1 fw-bold">Welcome, {{ ucwords($user->username) }}!</h2>
                                        <p class="mb-0 opacity-75">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="dashboard-clock-container">
                                    <div class="clock-display bg-white bg-opacity-15 rounded-3 p-2 d-inline-block">
                                        <div id="realtime-clock" class="fs-4 fw-bold mb-1 text-dark">00:00</div>
                                        <div id="realtime-date" class="small opacity-75 text-dark">Loading date...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Row -->
        <div class="row g-4 mb-4">
            <!-- Total Inventory -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="metric-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                    <i class="fas fa-boxes fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="metric-value fw-bold fs-3 text-dark">{{ number_format($inventoryCount) }}</div>
                                <div class="metric-label text-muted">Total Inventory Items</div>
                                <div class="metric-trend small">
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                        {{ $lowStockItems }} Low Stock</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="metric-icon bg-success bg-opacity-10 text-success rounded-3 p-3">
                                    <i class="fas fa-project-diagram fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="metric-value fw-bold fs-3 text-dark">{{ number_format($activeProjects) }}</div>
                                <div class="metric-label text-muted">Active Projects</div>
                                <div class="metric-trend small">
                                    <span class="text-success"><i class="fas fa-arrow-up"></i> {{ $projectsThisMonth }} This
                                        Month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="metric-icon bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                    <i class="fas fa-clock fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="metric-value fw-bold fs-3 text-dark">{{ number_format($pendingRequests) }}</div>
                                <div class="metric-label text-muted">Pending Requests</div>
                                <div class="metric-trend small">
                                    <span class="text-info"><i class="fas fa-info-circle"></i> {{ $totalRequests }}
                                        Total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Value -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="metric-icon bg-info bg-opacity-10 text-info rounded-3 p-3">
                                    <i class="fas fa-dollar-sign fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="metric-value fw-bold fs-3 text-dark">IDR
                                    {{ number_format($totalInventoryValue, 0, ',', '.') }}</div>
                                <div class="metric-label text-muted">Total Inventory Value</div>
                                <div class="metric-trend small">
                                    <span class="text-muted"><i class="fas fa-calculator"></i> Real-time Calculation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics Row -->
        <div class="row g-4 mb-4">
            <!-- Monthly Trends Chart -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">Monthly Trends</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                                    <li><a class="dropdown-item" href="#">Last Year</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="trendsChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Request Status Distribution -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Request Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="requestStatusChart" height="200"></canvas>
                        <div class="mt-3">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <div class="fs-4 fw-bold text-warning">{{ $pendingRequests }}</div>
                                    <div class="small text-muted">Pending</div>
                                </div>
                                <div class="col-6">
                                    <div class="fs-4 fw-bold text-success">{{ $deliveredRequests }}</div>
                                    <div class="small text-muted">Delivered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity and Insights Row -->
        <div class="row g-4 mb-4">
            <!-- Recent Activities -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">Recent Activities</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach ($recentRequests as $request)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="activity-icon bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-semibold">{{ $request->inventory->name ?? 'N/A' }}</div>
                                            <div class="small text-muted">
                                                Requested by {{ $request->user->username ?? 'N/A' }} •
                                                <span
                                                    class="badge {{ $request->getStatusBadgeClass() }}">{{ ucfirst($request->status) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 text-muted small">
                                            {{ $request->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">Upcoming Deadlines</h5>
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary">View
                                Projects</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($upcomingDeadlines->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($upcomingDeadlines as $project)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="deadline-icon bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-calendar-times"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="fw-semibold">{{ $project->name }}</div>
                                                <div class="small text-muted">
                                                    {{ $project->department->name ?? 'N/A' }} Department
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="text-danger small fw-semibold">
                                                    {{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-calendar-check fs-1 mb-3 opacity-25"></i>
                                <p>No upcoming deadlines in the next 30 days</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Overview and Quick Actions -->
        <div class="row g-4 mb-4">
            <!-- Department Overview -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Department Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($departmentStats as $dept)
                                <div class="col-md-6 col-lg-4">
                                    <div class="dept-card bg-light rounded-3 p-3 text-center">
                                        <div class="dept-icon mb-2">
                                            <i class="fas fa-building text-primary fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">{{ ucfirst($dept->name) }}</h6>
                                        <div class="small text-muted">
                                            {{ $dept->projects_count }} Projects • {{ $dept->users_count }} Users
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if (in_array($user->role, ['super_admin', 'admin_logistic']))
                                <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-boxes me-2"></i> Manage Inventory
                                </a>
                                <a href="{{ route('goods_out.index') }}" class="btn btn-outline-success">
                                    <i class="fas fa-shipping-fast me-2"></i> Goods Out
                                </a>
                            @endif

                            @if (in_array($user->role, ['super_admin', 'admin_mascot', 'admin_costume', 'admin_animatronic', 'general']))
                                <a href="{{ route('projects.index') }}" class="btn btn-outline-warning">
                                    <i class="fas fa-project-diagram me-2"></i> View Projects
                                </a>
                            @endif

                            @if (in_array($user->role, [
                                    'super_admin',
                                    'admin_mascot',
                                    'admin_costume',
                                    'admin_logistic',
                                    'admin_animatronic',
                                    'general',
                                ]))
                                <a href="{{ route('material_requests.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-clipboard-list me-2"></i> Material Requests
                                </a>
                            @endif

                            @if (in_array($user->role, ['super_admin', 'admin_finance']))
                                <a href="{{ route('costing.report') }}" class="btn btn-outline-info">
                                    <i class="fas fa-chart-bar me-2"></i> Cost Reports
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Super Admin Actions -->
        @if ($user->role === 'super_admin')
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold text-danger">
                                <i class="fas fa-tools me-2"></i> System Administration
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-2 col-sm-4 col-6">
                                    <button class="btn btn-outline-primary w-100 artisan-action"
                                        data-action="storage-link">
                                        <i class="bi bi-link d-block mb-1"></i>
                                        <small>Storage Link</small>
                                    </button>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <button class="btn btn-outline-danger w-100 artisan-action" data-action="clear-cache">
                                        <i class="bi bi-trash d-block mb-1"></i>
                                        <small>Clear Cache</small>
                                    </button>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <button class="btn btn-outline-warning w-100 artisan-action"
                                        data-action="config-clear">
                                        <i class="bi bi-gear d-block mb-1"></i>
                                        <small>Clear Config</small>
                                    </button>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <button class="btn btn-outline-success w-100 artisan-action"
                                        data-action="config-cache">
                                        <i class="bi bi-gear-fill d-block mb-1"></i>
                                        <small>Cache Config</small>
                                    </button>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <button class="btn btn-outline-info w-100 artisan-action" data-action="optimize">
                                        <i class="bi bi-lightning d-block mb-1"></i>
                                        <small>Optimize</small>
                                    </button>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <button class="btn btn-outline-secondary w-100 artisan-action"
                                        data-action="optimize-clear">
                                        <i class="bi bi-lightning-fill d-block mb-1"></i>
                                        <small>Clear Optimize</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        /* Professional ERP Dashboard Styles */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-icon,
        .deadline-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dept-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dept-card:hover {
            background-color: #e3f2fd !important;
            transform: translateY(-2px);
        }

        .clock-display {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .list-group-item {
            transition: background-color 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .metric-value {
                font-size: 1.5rem !important;
            }

            .dashboard-clock-container {
                text-align: center !important;
                margin-top: 1rem;
            }

            .avatar-wrapper {
                margin-bottom: 1rem;
            }
        }

        /* Chart container styling */
        #trendsChart,
        #requestStatusChart {
            max-height: 300px;
        }

        /* Animation for loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Custom badge colors */
        .text-bg-pending {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        /* Gradient effects for cards */
        .card {
            border-radius: 12px;
        }

        .rounded-3 {
            border-radius: 12px !important;
        }

        /* Professional shadows */
        .shadow-sm {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }

        .shadow-lg {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Charts
            initializeTrendsChart();
            initializeRequestStatusChart();

            // Initialize Clock
            initializeClock();

            // Initialize Artisan Actions
            initializeArtisanActions();
        });

        // Monthly Trends Chart
        function initializeTrendsChart() {
            const ctx = document.getElementById('trendsChart');
            if (!ctx) return;

            const monthlyData = @json($monthlyData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Projects',
                        data: monthlyData.map(item => item.projects),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Goods In',
                        data: monthlyData.map(item => item.goods_in),
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Goods Out',
                        data: monthlyData.map(item => item.goods_out),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Requests',
                        data: monthlyData.map(item => item.requests),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6
                        }
                    }
                }
            });
        }

        // Request Status Doughnut Chart
        function initializeRequestStatusChart() {
            const ctx = document.getElementById('requestStatusChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Delivered'],
                    datasets: [{
                        data: [{{ $pendingRequests }}, {{ $approvedRequests }},
                            {{ $deliveredRequests }}],
                        backgroundColor: [
                            '#ffc107',
                            '#007bff',
                            '#28a745'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // Real-time Clock
        function initializeClock() {
            const serverTime = new Date("{{ $serverTime->format('Y-m-d\TH:i:sP') }}");
            let clientTime = new Date();
            const timeOffset = serverTime.getTime() - clientTime.getTime();

            function updateClock() {
                const now = new Date(Date.now() + timeOffset);
                const pad = n => n.toString().padStart(2, '0');
                const time = `${pad(now.getHours())}:${pad(now.getMinutes())}`;

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

                const clockElement = document.getElementById('realtime-clock');
                const dateElement = document.getElementById('realtime-date');

                if (clockElement) clockElement.textContent = time;
                if (dateElement) dateElement.textContent = fullDate;
            }

            updateClock();
            setInterval(updateClock, 10000);
        }

        // Artisan Actions
        function initializeArtisanActions() {
            document.querySelectorAll('.artisan-action').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.dataset.action;

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
        }

        // Auto-refresh data every 5 minutes
        setInterval(function() {
            if (!document.hidden) {
                location.reload();
            }
        }, 300000);
    </script>
@endpush
