<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Padel Hub | @yield('title', 'Dashboard')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        .preloader {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .ball-loader {
            display: flex;
            gap: 12px;
        }
        .ball-loader .ball {
            width: 15px;
            height: 15px;
            background-color: #007bff;
            border-radius: 50%;
            animation: ball-bounce 0.8s infinite ease-in-out alternate;
        }
        .ball-loader .ball:nth-child(2) {
            animation-delay: 0.2s;
        }
        .ball-loader .ball:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes ball-bounce {
            from {
                transform: translateY(10px);
                opacity: 0.3;
            }
            to {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        /* Sidebar Push Layout on Hover */
        @media (min-width: 992px) {
            .sidebar-mini.sidebar-collapse .main-sidebar:hover ~ .content-wrapper,
            .sidebar-mini.sidebar-collapse .main-sidebar:hover ~ .main-header,
            .sidebar-mini.sidebar-collapse .main-sidebar:hover ~ .main-footer {
                margin-left: 250px !important;
                transition: margin-left .3s ease-in-out;
            }
            
            .sidebar-mini.sidebar-collapse .main-sidebar {
                transition: width .3s ease-in-out;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <div class="ball-loader">
            <div class="ball"></div>
            <div class="ball"></div>
            <div class="ball"></div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span class="d-none d-md-inline mr-2 text-dark font-weight-bold">{{ Auth::user()->name }}</span>
                    <div class="img-circle bg-primary d-inline-flex align-items-center justify-content-center text-white font-weight-bold shadow-sm" style="width: 32px; height: 32px;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <div class="img-circle bg-white d-inline-flex align-items-center justify-content-center text-primary font-weight-bold shadow-sm mb-2" style="width: 60px; height: 60px; font-size: 24px;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <p>
                            {{ Auth::user()->name }}
                            <small>Administrator</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-default btn-flat float-right text-danger font-weight-bold rounded">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link border-bottom-0 shadow-sm text-center">
            <span class="brand-text font-weight-bold">PADEL <span class="text-primary">HUB</span></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header text-uppercase small opacity-50 font-weight-bold mb-2">Main Menu</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active shadow' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase small opacity-50 font-weight-bold mt-4 mb-2">Management</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.courts.index') }}" class="nav-link {{ request()->routeIs('admin.courts.*') ? 'active shadow' : '' }}">
                            <i class="nav-icon fas fa-table"></i>
                            <p>Lapangan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active shadow' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>Booking & Pembayaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.membership-tiers.index') }}" class="nav-link {{ request()->routeIs('admin.membership-tiers.*') ? 'active shadow' : '' }}">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Membership Tiers</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase small opacity-50 font-weight-bold mt-4 mb-2">Settings</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.landing.edit') }}" class="nav-link {{ request()->routeIs('admin.landing.*') ? 'active shadow' : '' }}">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>Landing Page</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper bg-light">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-dark">@yield('header')</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer border-0 shadow-sm text-sm">
        <strong>Copyright &copy; 2026 <a href="#">Padel Booking</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(window).on('load', function() {
        setTimeout(function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#fff',
                    color: '#333'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Opps!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#dc3545'
                });
            @endif
        }, 500);
    });

    // Global SweetAlert2 Confirmation for Deletion
    $(document).on('submit', '.delete-confirm', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>

