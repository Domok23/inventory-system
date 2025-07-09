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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/3.0.1/css/select.bootstrap5.css">
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

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @push('styles')
        <style>
            html,
            body {
                height: 100%;
                background-color: #F5F6FA
            }

            /* body {
                                            font-family: "Helvetica Neue", Inter, Nunito, Roboto, Poppins, Arial, sans-serif;
                                    } */

            #app {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            main {
                flex: 1;
            }

            .nav-link.active {
                font-weight: bold;
                position: relative;
                /* Posisi relatif untuk elemen */
            }

            .nav-link.active::after {
                content: '';
                position: absolute;
                /* Posisi absolut untuk garis bawah */
                bottom: 0;
                /* Garis mentok di bawah */
                left: 0;
                width: 100%;
                /* Panjang garis mengikuti elemen */
                height: 2px;
                /* Ketebalan garis */
                background: linear-gradient(45deg, #8F12FE, #4A25AA);
                /* Warna garis sesuai tema Bootstrap */
            }

            .navbar-brand {
                background: linear-gradient(45deg, #8F12FE, #4A25AA);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                color: transparent !important;
                font-weight: bold;
                transition: background 0.3s, color 0.3s;
            }

            /* (Opsional) Saat hover, tetap gunakan gradasi yang sama atau tambahkan efek lain */
            .navbar-brand:hover,
            .navbar-brand:focus {
                filter: brightness(1.2);
            }

            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link:focus {
                background: linear-gradient(45deg, #8F12FE, #4A25AA);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                transition: background 0.3s, color 0.3s;
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

            #toast-container {
                scrollbar-width: thin;
                scrollbar-color: #007bff #f1f1f1;
            }

            #toast-container::-webkit-scrollbar {
                width: 8px;
            }

            #toast-container::-webkit-scrollbar-thumb {
                background-color: #007bff;
                border-radius: 4px;
            }

            #toast-container::-webkit-scrollbar-track {
                background-color: #f1f1f1;
            }

            .select2-results__option[aria-selected="false"].text-muted {
                color: #6c757d !important;
                /* Warna abu-abu Bootstrap */
            }

            .status-select option[value="pending"] {
                background-color: #ffffff;
                /* Warna kuning */
                color: #000000;
                /* Warna teks hitam */
            }

            .status-select option[value="approved"] {
                background-color: #ffffff;
                /* Warna biru */
                color: #000000;
                /* Warna teks putih */
            }

            .status-select option[value="delivered"] {
                background-color: #ffffff;
                /* Warna hijau */
                color: #000000;
                /* Warna teks putih */
            }

            .status-select option[value="canceled"] {
                background-color: #ffffff;
                /* Warna merah */
                color: #000000;
                /* Warna teks putih */
            }

            .status-pending {
                background-color: #ffc107 !important;
                /* Warna kuning */
                color: #000 !important;
                /* Warna teks hitam */
            }

            .status-approved {
                background-color: #0d6efd !important;
                /* Warna biru */
                color: #fff !important;
                /* Warna teks putih */
            }

            .status-delivered {
                background-color: #198754 !important;
                /* Warna hijau */
                color: #fff !important;
                /* Warna teks putih */
            }

            .status-canceled {
                background-color: #dc3545 !important;
                /* Warna merah */
                color: #fff !important;
                /* Warna teks putih */
            }

            .badge {
                padding: 0.5em 0.5em;
            }

            .img-hover {
                cursor: zoom-in;
                /* Ubah cursor menjadi kaca pembesar saat hover */
            }

            .img-hover:hover {
                transform: scale(1.02);
                /* Perbesar gambar saat hover */
                transition: transform 0.3s ease;
                /* Tambahkan transisi halus */
            }

            .fancybox__container {
                z-index: 1060 !important;
                /* Pastikan lebih tinggi dari modal Bootstrap */
            }

            footer,
            footer .container-fluid,
            footer .row,
            footer .bg-white,
            footer .text-center {
                font-size: 0.85rem;
            }

            footer h5 {
                font-size: 1rem;
            }

            footer p,
            footer .text-secondary {
                font-size: 0.8rem;
            }
        </style>
    @endpush
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-4 rounded-top-0">
            <div class="container-fluid">
                <a class="navbar-brand" style="font-weight: bold;" href="{{ url('/') }}">
                    {{ config('app.name', 'DCM-app') }}
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
                            @if (in_array(auth()->user()->role, [
                                    'super_admin',
                                    'admin_mascot',
                                    'admin_costume',
                                    'admin_logistic',
                                    'admin_finance',
                                    'admin_animatronic',
                                    'general',
                                ]))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}"
                                        href="{{ route('inventory.index') }}">
                                        Inventory
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, [
                                    'super_admin',
                                    'admin_mascot',
                                    'admin_costume',
                                    'admin_logistic',
                                    'admin_finance',
                                    'admin_animatronic',
                                    'general',
                                ]))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                                        href="{{ route('projects.index') }}">
                                        Project
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, [
                                    'super_admin',
                                    'admin_mascot',
                                    'admin_costume',
                                    'admin_logistic',
                                    'admin_finance',
                                    'admin_animatronic',
                                    'general',
                                ]))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('material_requests*') ? 'active' : '' }}"
                                        href="{{ route('material_requests.index') }}">
                                        Material Request
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, [
                                    'super_admin',
                                    'admin_mascot',
                                    'admin_costume',
                                    'admin_logistic',
                                    'admin_finance',
                                    'admin_animatronic',
                                    'general',
                                ]))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('goods_out*') ? 'active' : '' }}"
                                        href="{{ route('goods_out.index') }}">
                                        Goods Out
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, [
                                    'super_admin',
                                    'admin_mascot',
                                    'admin_costume',
                                    'admin_logistic',
                                    'admin_finance',
                                    'admin_animatronic',
                                    'general',
                                ]))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('goods_in*') ? 'active' : '' }}"
                                        href="{{ route('goods_in.index') }}">
                                        Goods In
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('material-usage*') ? 'active' : '' }}"
                                        href="{{ route('material_usage.index') }}">
                                        Material Usage
                                    </a>
                                </li>
                            @endif
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin_finance']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('costing-report*') ? 'active' : '' }}"
                                        href="{{ route('costing.report') }}">
                                        Costing Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('currencies*') ? 'active' : '' }}"
                                        href="{{ route('currencies.index') }}">
                                        Currency
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->role === 'super_admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                                        href="{{ route('users.index') }}">
                                        User
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('trash.index') ? 'active' : '' }}"
                                        href="{{ route('trash.index') }}">
                                        Trash
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
                                <a id="navbarDropdown" class="nav-link btn dropdown-toggle" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Hi, {{ ucfirst(Auth::user()->username) }}!
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
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
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container"
        style="max-height: 50vh; overflow-y: auto;">
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

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- Setup CSRF Token for AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

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
    <script src="https://cdn.datatables.net/select/3.0.1/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/select/3.0.1/js/select.bootstrap5.js"></script>
    <!-- DataTables Responsive JS -->
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        const authUserRole = "{{ auth()->check() ? auth()->user()->role : '' }}";
    </script>
    <script>
        function updateSelectColor(selectElement) {
            // Hapus semua kelas status dari elemen <select>
            selectElement.classList.remove("status-pending", "status-approved", "status-delivered", "status-canceled");

            // Tambahkan kelas berdasarkan nilai yang dipilih
            const selectedValue = selectElement.value;
            if (selectedValue === "pending") {
                selectElement.classList.add("status-pending");
            } else if (selectedValue === "approved") {
                selectElement.classList.add("status-approved");
            } else if (selectedValue === "delivered") {
                selectElement.classList.add("status-delivered");
            } else if (selectedValue === "canceled") {
                selectElement.classList.add("status-canceled");
            }
        }

        // Terapkan fungsi ke semua elemen <select> dengan kelas .status-select
        document.addEventListener("DOMContentLoaded", () => {
            const statusSelectElements = document.querySelectorAll(".status-select");
            statusSelectElements.forEach((selectElement) => {
                // Perbarui warna saat halaman dimuat
                updateSelectColor(selectElement);

                // Perbarui warna saat nilai berubah
                selectElement.addEventListener("change", () => {
                    updateSelectColor(selectElement);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan padding pada body saat SweetAlert aktif
            Swal.mixin({
                didOpen: () => {
                    document.body.style.paddingRight = '15px'; // Sesuaikan dengan scrollbar
                },
                didClose: () => {
                    document.body.style.paddingRight = ''; // Hapus padding setelah modal ditutup
                }
            });
        });
    </script>
    @stack('scripts')

    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="container-fluid">
            <div class="row">
                <!-- About Section -->
                <div class="bg-white col-lg-12 col-lg-12 mb-2 mb-lg-0">
                    <h5 class="text-uppercase mt-2">About:</h5>
                    <p class="mb-3">
                        This is an inventory management system designed to streamline your operations and improve
                        efficiency.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center p-1 bg-dark text-secondary">
            © {{ date('Y') }} {{ config('app.name', 'DCM-app') }}. Created with <i
                class="fas fa-heart text-danger"></i> by IT Team (Gen 1)
        </div>
    </footer>
</body>

</html>
