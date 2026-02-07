<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Attendance System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3B82F6;
            --sidebar-width: 280px;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #3B82F6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar .logo {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .logo h4 {
            font-weight: 700;
            margin: 0;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            margin-left: 10px;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: var(--sidebar-hover);
        }

        .sidebar .nav-link.active {
            color: white;
            background: var(--sidebar-active);
        }

        .sidebar .nav-link i {
            width: 24px;
            margin-right: 10px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar .nav-item {
            margin: 0 5px;
        }

        .navbar .nav-link {
            color: #555;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: var(--primary-color);
        }

        .content {
            padding: 30px;
            min-height: calc(100vh - 56px);
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }

        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.primary {
            border-left-color: #3B82F6;
        }

        .stat-card.success {
            border-left-color: #10B981;
        }

        .stat-card.warning {
            border-left-color: #F59E0B;
        }

        .stat-card.danger {
            border-left-color: #EF4444;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h4><i class="bi bi-calendar-check-fill"></i> Attendance System</h4>
        </div>
        <div class="p-3">
            <small class="text-muted">Welcome, {{ Auth::user()->name }}</small>
            <div class="text-capitalize fw-bold">{{ Auth::user()->role }}</div>
        </div>
        <hr class="text-white-50 mx-3">

        <ul class="nav flex-column px-3">
            @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Karyawan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                    </a>
                </li>
                @if(Auth::user()->isSuperAdmin())
                <li class="nav-item">
                    <a href="{{ route('devices.index') }}" class="nav-link {{ request()->routeIs('devices.*') ? 'active' : '' }}">
                        <i class="bi bi-server"></i> Mesin Absen
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i> Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backup.index') }}" class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                        <i class="bi bi-database-fill-gear"></i> Backup
                    </a>
                </li>
                @endif
            @else
                <li class="nav-item">
                    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.attendance') }}" class="nav-link {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.requests') }}" class="nav-link {{ request()->routeIs('employee.requests.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i> Request Dinas Luar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.profile') }}" class="nav-link {{ request()->routeIs('employee.profile') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </a>
                </li>
            @endif
        </ul>

        <div class="px-3 mt-auto mb-3">
            <hr class="text-white-50">
            <a href="{{ route('logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ Auth::user()->isEmployee() ? route('employee.profile') : '#' }}">
                                    <i class="bi bi-person"></i> Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>