<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CivicLens Admin Panel">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - CivicLens Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            margin: 0;
        }

        /* Admin top bar */
        .admin-topbar {
            background: var(--cl-white);
            border-bottom: 1px solid var(--cl-border);
            padding: 0.6rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .admin-topbar .brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--cl-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Sidebar */
        .admin-layout {
            display: flex;
            min-height: calc(100vh - 52px);
        }
        .admin-sidebar {
            background: var(--cl-white);
            border-right: 1px solid var(--cl-border);
            width: 240px;
            padding: 1rem 0;
            flex-shrink: 0;
        }
        .admin-sidebar .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.5rem;
            color: var(--cl-gray);
            text-decoration: none;
            font-size: 0.875rem;
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
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            font-weight: 600;
            margin-top: 1rem;
        }

        /* Content area */
        .admin-content {
            flex: 1;
            padding: 1.5rem 2rem;
            overflow-x: auto;
        }

        /* Stat cards */
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
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--cl-gray);
            font-weight: 500;
        }

        .cl-card {
            background: var(--cl-white);
            border: 1px solid var(--cl-border);
            border-radius: 10px;
            overflow: hidden;
        }

        .alert { border-radius: 8px; border: none; font-size: 0.9rem; }

        .form-control:focus, .form-select:focus {
            border-color: var(--cl-primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,0.1);
        }

        .table { font-size: 0.875rem; }
        .table th { font-weight: 600; color: var(--cl-gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Admin Top Bar -->
    <div class="admin-topbar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <i class="bi bi-shield-check"></i> CivicLens Admin
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="text-muted" style="font-size:0.85rem; text-decoration:none;">
                <i class="bi bi-box-arrow-up-right"></i> View Site
            </a>
            <span class="text-muted" style="font-size:0.85rem;">
                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size:0.8rem;">Logout</button>
            </form>
        </div>
    </div>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-heading">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <div class="sidebar-heading">Content</div>
            <a href="{{ route('admin.news.index') }}" class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> News Articles
            </a>
            <a href="{{ route('admin.news.create') }}" class="sidebar-link">
                <i class="bi bi-plus-circle"></i> Add News
            </a>

            <div class="sidebar-heading">Engagement</div>
            <a href="{{ route('admin.feedback.index') }}" class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text"></i> Feedback
            </a>
            <a href="{{ route('admin.analytics') }}" class="sidebar-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Analytics
            </a>
        </aside>

        <!-- Main Content -->
        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>
</html>
