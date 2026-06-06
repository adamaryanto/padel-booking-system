<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Padel Hub | @yield('title', 'Dashboard')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        html {
            font-size: 92.5% !important;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: #f9fafb !important;
            color: #1f2937 !important;
        }
        .content-wrapper {
            background-color: #f9fafb !important;
        }
        
        /* Modern Sidebar (Linear/Vercel style) */
        .main-sidebar {
            background-color: #ffffff !important;
            border-right: 1px solid #e5e7eb !important;
            box-shadow: none !important;
        }
        .brand-link {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            color: #111827 !important;
            padding: 1.25rem 1rem !important;
            font-size: 1.1rem !important;
            font-weight: 800 !important;
        }
        .brand-link:hover {
            color: #111827 !important;
        }
        .sidebar {
            background-color: #ffffff !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .nav-sidebar .nav-link {
            border-radius: 0.75rem !important;
            color: #4b5563 !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            padding: 0.625rem 0.875rem !important;
            margin-bottom: 0.25rem !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
        }
        .nav-sidebar .nav-link i {
            color: #9ca3af !important;
            margin-right: 0.75rem !important;
            font-size: 1.1rem !important;
            width: 1.25rem !important;
            text-align: center !important;
            transition: color 0.2s ease-in-out !important;
        }
        .nav-sidebar .nav-link:hover {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
        }
        .nav-sidebar .nav-link:hover i {
            color: #4b5563 !important;
        }
        .nav-sidebar .nav-link.active {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #059669 !important;
            box-shadow: none !important;
        }
        .nav-sidebar .nav-link.active i {
            color: #10b981 !important;
        }
        .nav-header {
            color: #9ca3af !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 1.25rem 0.875rem 0.5rem !important;
        }
        
        /* Child Menu Indentation */
        .nav-treeview {
            padding-left: 1.5rem !important;
        }
        .nav-treeview .nav-link {
            font-size: 0.825rem !important;
            padding: 0.5rem 0.75rem !important;
        }
        
        /* Flat Top Navbar */
        .main-header.navbar {
            background-color: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border-bottom: 1px solid #e5e7eb !important;
            box-shadow: none !important;
            padding: 0.75rem 1.5rem !important;
        }
        .main-header.navbar .nav-link {
            color: #4b5563 !important;
        }
        .main-header.navbar .nav-link:hover {
            color: #111827 !important;
        }
        
        /* Modern Cards (Linear / Vercel style) */
        .card {
            background-color: #ffffff !important;
            border-radius: 1.25rem !important; /* rounded-2xl */
            border: 1px solid #e5e7eb !important; /* Soft gray border */
            box-shadow: none !important;
            margin-bottom: 1.5rem !important;
            overflow: hidden !important;
        }
        .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 1.25rem 1.5rem !important;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        .card-footer {
            background-color: #f9fafb !important;
            border-top: 1px solid #e5e7eb !important;
            padding: 1.25rem 1.5rem !important;
        }
        
        /* Emerald Green Buttons */
        .btn {
            border-radius: 0.75rem !important; /* rounded-xl */
            padding: 0.6rem 1.25rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            border: 1px solid transparent !important;
        }
        .btn-primary {
            background-color: #10b981 !important; /* Emerald Accent */
            border-color: #10b981 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover {
            background-color: #059669 !important;
            border-color: #059669 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15) !important;
        }
        .btn-success {
            background-color: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
        }
        .btn-success:hover {
            background-color: #047857 !important;
            border-color: #047857 !important;
            transform: translateY(-1px) !important;
        }
        .btn-outline-primary {
            border-color: #e5e7eb !important;
            background-color: #ffffff !important;
            color: #374151 !important;
        }
        .btn-outline-primary:hover {
            background-color: #f9fafb !important;
            color: #111827 !important;
            border-color: #d1d5db !important;
        }
        .btn-danger {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
        }
        .btn-danger:hover {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            transform: translateY(-1px) !important;
        }
        .btn-warning {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
        }
        .btn-warning:hover {
            background-color: #d97706 !important;
            border-color: #d97706 !important;
        }
        
        /* Modern Inputs (Border Gray 200) */
        .form-control, .custom-select, select {
            border: 1px solid #e5e7eb !important; /* Gray 200 */
            border-radius: 0.75rem !important; /* rounded-xl */
            padding: 0.625rem 0.875rem !important;
            font-size: 0.875rem !important;
            background-color: #ffffff !important;
            color: #1f2937 !important;
            height: auto !important;
            transition: all 0.2s ease-in-out !important;
        }
        .form-control:focus, .custom-select:focus, select:focus {
            border-color: #10b981 !important; /* Emerald accent focus */
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
            outline: none !important;
        }
        .form-group label {
            font-size: 0.85rem !important;
            color: #374151 !important;
            font-weight: 600 !important;
            margin-bottom: 0.5rem !important;
        }
        
        /* Clean Tables */
        .table th {
            border-top: 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            font-weight: 700 !important;
            color: #6b7280 !important;
            padding: 1rem 1.25rem !important;
            background-color: #f9fafb !important;
        }
        .table td {
            border-top: 1px solid #e5e7eb !important;
            padding: 1.25rem !important;
            vertical-align: middle !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
        }
        .table tbody tr:hover {
            background-color: #f9fafb !important;
        }
        
        /* Custom Badges */
        .badge {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            padding: 0.35rem 0.75rem !important;
            border-radius: 9999px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
        }
        .badge-success {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #059669 !important;
        }
        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #d97706 !important;
        }
        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #dc2626 !important;
        }
        .badge-info {
            background-color: rgba(59, 130, 246, 0.1) !important;
            color: #2563eb !important;
        }
        
        /* Profile Dropdown Hover Dropdown */
        .user-menu:hover .dropdown-menu {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
        .user-menu .dropdown-menu {
            display: block !important;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease-in-out;
            margin-top: 0 !important;
            background-color: #ffffff !important;
        }
        
        /* Flex Gap Utility Backports for Bootstrap 4 */
        .gap-1 { gap: 0.25rem !important; }
        .gap-2 { gap: 0.5rem !important; }
        .gap-3 { gap: 1rem !important; }
        .gap-4 { gap: 1.5rem !important; }

        /* Input group border-radius and boundary merging fixes */
        .input-group .form-control:not(:first-child) {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-left: 0 !important;
        }
        .input-group-prepend .input-group-text {
            border: 1px solid #e5e7eb !important;
            border-right: 0 !important;
            border-top-left-radius: 0.75rem !important;
            border-bottom-left-radius: 0.75rem !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            background-color: #ffffff !important;
        }

        /* Sidebar Narrower Width & Compact Spacing */
        @media (min-width: 768px) {
            .main-sidebar, .main-sidebar .brand-link {
                width: 220px !important;
                transition: width 0.3s ease-in-out !important;
            }
            .content-wrapper, .main-header, .main-footer {
                margin-left: 220px !important;
                transition: margin-left 0.3s ease-in-out !important;
            }
            body.sidebar-collapse .main-sidebar {
                width: 4.6rem !important;
            }
            body.sidebar-collapse .content-wrapper,
            body.sidebar-collapse .main-header,
            body.sidebar-collapse .main-footer {
                margin-left: 4.6rem !important;
            }
        }
        
        .nav-sidebar .nav-link {
            padding: 0.5rem 0.75rem !important;
            margin-bottom: 0.15rem !important;
        }
        .nav-header {
            padding: 0.75rem 0.75rem 0.25rem !important;
            margin-top: 0.75rem !important;
            margin-bottom: 0.25rem !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
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
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <!-- Search Bar -->
            <li class="nav-item d-none d-sm-inline-block ml-3">
                <div class="input-group input-group-sm" style="width: 240px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0 border-gray-200" style="border-radius: 0.5rem 0 0 0.5rem;"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-navbar bg-light border-left-0 border-gray-200" placeholder="Search..." style="border-radius: 0 0.5rem 0.5rem 0; height: 31px !important; padding: 0.25rem 0.5rem !important;">
                </div>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto align-items-center">
            <!-- Notification Icon -->
            <li class="nav-item mr-3">
                <a class="nav-link position-relative p-1" href="#" role="button">
                    <i class="far fa-bell text-muted" style="font-size: 1.2rem;"></i>
                    <span class="badge badge-danger navbar-badge position-absolute" style="top: -2px; right: -2px; padding: 0.15rem 0.35rem !important; font-size: 0.6rem !important;">3</span>
                </a>
            </li>
            <!-- Profile -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                    <span class="d-none d-md-inline mr-2 text-dark font-weight-bold" style="font-size: 0.85rem;">{{ Auth::user()->name }}</span>
                    <div class="img-circle bg-success d-inline-flex align-items-center justify-content-center text-white font-weight-bold shadow-sm" style="width: 32px; height: 32px; background-color: #10b981 !important;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right shadow-lg border-0 rounded-lg p-2" style="min-width: 200px;">
                    <!-- User Info header -->
                    <div class="px-3 py-2">
                        <span class="d-block text-dark font-weight-bold text-sm">{{ Auth::user()->name }}</span>
                        <span class="d-block text-muted text-xs">Administrator</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    
                    <!-- Edit Profile Link -->
                    <a href="{{ route('profile.edit') }}" class="dropdown-item px-3 py-2 rounded text-sm d-flex align-items-center text-secondary">
                        <i class="fas fa-user-edit mr-2 text-success"></i> Edit Profile
                    </a>
                    
                    <!-- Logout -->
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item px-3 py-2 rounded text-sm d-flex align-items-center text-danger border-0 w-100 bg-transparent" style="cursor: pointer;">
                            <i class="fas fa-sign-out-alt mr-2 text-danger"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-0">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link border-bottom-0 text-center">
            <span class="brand-text">🏓 PadelHub Admin</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column nav-flat" role="menu" data-accordion="false">
                    
                    <li class="nav-header text-uppercase small opacity-50 font-weight-bold mb-2">Main Menu</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase small opacity-50 font-weight-bold mt-4 mb-2">Management</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.courts.index') }}" class="nav-link {{ request()->routeIs('admin.courts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>Lapangan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Booking & Pembayaran</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.membership-tiers.index') }}" class="nav-link {{ request()->routeIs('admin.membership-tiers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>Tiers Membership</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-question-circle"></i>
                            <p>Kelola FAQ</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase small opacity-50 font-weight-bold mt-4 mb-2">Settings</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.landing.edit') }}" class="nav-link {{ request()->routeIs('admin.landing.edit') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-edit"></i>
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

