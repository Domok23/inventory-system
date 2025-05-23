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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @push('styles')
        <style>
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
        </style>
    @endpush
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
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
                            @endif
                            @auth
                                @if (auth()->user()->role === 'super_admin')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('trash.index') ? 'active' : '' }}"
                                            href="{{ route('trash.index') }}">
                                            <i class="bi bi-trash"></i> Trash
                                        </a>
                                    </li>
                                @endif
                            @endauth
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
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
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (required by DataTables & SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <!-- DataTables Responsive JS -->
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
