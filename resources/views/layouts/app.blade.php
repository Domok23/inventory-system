<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @push('styles')
        <style>
            html,
            body {
                height: 100%;
                background-color: #F5F6FA
            }

            #app {
                min-height: 100%;
                display: flex;
                flex-direction: column;
            }

            main {
                flex: 1;
            }

            .nav-link.active {
                font-weight: bold;
                border-bottom: 2px solid var(--bs-primary);
                /* Garis bawah warna primary */
            }

            td .btn {
                margin-bottom: 0.25rem;
                text-align: center;
            }

            .delete-form {
                margin: 0;
                /* Hilangkan margin default pada form */
            }

            .card {
                border: none;
                /* Menghilangkan border pada card */
            }

            .toast-container {
                z-index: 1055;
            }

            .toast-container .toast {
                margin-bottom: 0.5rem;
            }
        </style>
    @endpush
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow rounded-4 rounded-top-0">
            <div class="container-fluid">
                <a class="navbar-brand" style="font-weight: bold;" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @if (auth()->check())
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('inventory*') ? 'active text-primary' : '' }}"
                                        href="{{ route('inventory.index') }}">
                                        Inventory
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_mascot', 'admin_costume']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('projects*') ? 'active text-primary' : '' }}"
                                        href="{{ route('projects.index') }}">
                                        Projects
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_mascot', 'admin_costume', 'admin_logistic', 'admin_finance']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('material_requests*') ? 'active text-primary' : '' }}"
                                        href="{{ route('material_requests.index') }}">
                                        Material Requests
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_logistic']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('goods_out*') ? 'active text-primary' : '' }}"
                                        href="{{ route('goods_out.index') }}">
                                        Goods Out
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_mascot', 'admin_costume', 'admin_logistic', 'admin_finance']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('goods_in*') ? 'active text-primary' : '' }}"
                                        href="{{ route('goods_in.index') }}">
                                        Goods In
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('material-usage*') ? 'active text-primary' : '' }}"
                                        href="{{ route('material_usage.index') }}">
                                        Material Usage
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_finance']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('costing-report*') ? 'active text-primary' : '' }}"
                                        href="{{ route('costing.report') }}">
                                        Costing Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('currencies*') ? 'active text-primary' : '' }}"
                                        href="{{ route('currencies.index') }}">
                                        Currencies
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->role === 'super_admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('users*') ? 'active text-primary' : '' }}"
                                        href="{{ route('users.index') }}">
                                        Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('trash.index') ? 'active' : '' }}"
                                        href="{{ route('trash.index') }}">
                                        <i class="bi bi-trash"></i> Trash
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link btn btn-warning dropdown-toggle" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->username }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container">
        <!-- Template toast kosong -->
        <div class="toast d-none" id="toast-template" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <span class="rounded-circle bg-primary d-inline-block" style="width: 10px; height: 10px;"></span>
                <strong class="me-auto ms-2">Material Request</strong>
                <small class="toast-time">Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <!-- Konten toast akan diisi melalui JavaScript -->
            </div>
        </div>
    </div>

    <div id="notification-sound-container">
        <audio id="notification-sound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>
    </div>

    <!-- Scripts -->
    <!-- jQuery (required by DataTables & SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables JS -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <!-- DataTables Responsive JS -->
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        const authUserRole = "{{ auth()->check() ? auth()->user()->role : '' }}";
    </script>
    @stack('scripts')

    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="container-fluid">
            <div class="row">
                <!-- About Section -->
                <div class="bg-white col-lg-12 col-md-12 mb-2 mb-md-0">
                    <h5 class="text-uppercase mt-2">About:</h5>
                    <p class="mb-3">
                        This is an inventory management system designed to streamline your operations and improve
                        efficiency.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center p-1 bg-dark text-secondary">
            © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Created with <i
                class="fas fa-heart text-danger"></i> by IT Team (Gen 1)
        </div>
    </footer>
</body>

</html>
