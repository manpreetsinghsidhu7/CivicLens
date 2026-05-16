<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CivicLens - A 360° Feedback System for Government News in India">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CivicLens') - Government News Feedback</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --cl-primary: #1a56db;
            --cl-primary-light: #e8eefb;
            --cl-dark: #111827;
            --cl-gray: #6b7280;
            --cl-gray-light: #f3f4f6;
            --cl-border: #e5e7eb;
            --cl-white: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--cl-gray-light);
            color: var(--cl-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .cl-navbar {
            background: var(--cl-white);
            border-bottom: 1px solid var(--cl-border);
            padding: 0.75rem 0;
        }
        .cl-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--cl-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cl-navbar .navbar-brand i {
            font-size: 1.5rem;
        }
        .cl-navbar .nav-link {
            color: var(--cl-gray);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s;
        }
        .cl-navbar .nav-link:hover,
        .cl-navbar .nav-link.active {
            color: var(--cl-primary);
        }
        .cl-navbar .btn-login {
            background: var(--cl-primary);
            color: #fff;
            border: none;
            padding: 0.45rem 1.25rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .cl-navbar .btn-login:hover {
            background: #1347b8;
            color: #fff;
        }
        .cl-navbar .btn-outline-login {
            border: 1px solid var(--cl-border);
            color: var(--cl-dark);
            padding: 0.45rem 1.25rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            background: transparent;
        }
        .cl-navbar .btn-outline-login:hover {
            border-color: var(--cl-primary);
            color: var(--cl-primary);
        }

        /* Main Content */
        main { flex: 1; }

        /* Footer */
        .cl-footer {
            background: var(--cl-white);
            border-top: 1px solid var(--cl-border);
            padding: 1.5rem 0;
            color: var(--cl-gray);
            font-size: 0.85rem;
        }

        /* Cards */
        .cl-card {
            background: var(--cl-white);
            border: 1px solid var(--cl-border);
            border-radius: 10px;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .cl-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }

        /* Stat Cards */
        .stat-card {
            background: var(--cl-white);
            border: 1px solid var(--cl-border);
            border-radius: 10px;
            padding: 1.25rem;
        }
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--cl-dark);
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--cl-gray);
            font-weight: 500;
        }

        /* Skeleton Loading */
        .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .skeleton-card {
            background: var(--cl-white);
            border: 1px solid var(--cl-border);
            border-radius: 10px;
            overflow: hidden;
        }
        .skeleton-img {
            height: 180px;
            width: 100%;
        }
        .skeleton-line {
            height: 14px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        .skeleton-line.short { width: 60%; }
        .skeleton-line.medium { width: 80%; }
        .skeleton-line.full { width: 100%; }

        /* Alert styles */
        .alert { border-radius: 8px; border: none; font-size: 0.9rem; }

        /* Badge */
        .cl-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Admin Sidebar */
        .admin-sidebar {
            background: var(--cl-white);
            border-right: 1px solid var(--cl-border);
            min-height: calc(100vh - 60px);
            padding: 1.5rem 0;
            width: 250px;
        }
        .admin-sidebar .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: var(--cl-gray);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s;
        }
        .admin-sidebar .sidebar-link:hover,
        .admin-sidebar .sidebar-link.active {
            color: var(--cl-primary);
            background: var(--cl-primary-light);
        }
        .admin-sidebar .sidebar-heading {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            font-weight: 600;
            margin-top: 1rem;
        }

        /* Form styles */
        .form-control:focus, .form-select:focus {
            border-color: var(--cl-primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,0.1);
        }

        /* Star rating display */
        .star-rating .bi-star-fill { color: #f59e0b; }
        .star-rating .bi-star { color: #d1d5db; }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="cl-navbar navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shield-check"></i> CivicLens
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">News</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">My Dashboard</a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-login">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-login">Register</a>
                    @else
                        <span class="text-muted me-2" style="font-size:0.85rem;">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-login btn-sm">Logout</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="cl-footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <strong style="color:var(--cl-primary);">CivicLens</strong> &mdash; 360° Feedback for Government News
                </div>
                <div class="col-md-6 text-md-end">
                    &copy; {{ date('Y') }} CivicLens &middot; Built with Laravel MVC
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    @yield('scripts')
</body>
</html>
