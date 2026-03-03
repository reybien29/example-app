<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Workspace')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink: #0f1117;
            --ink-muted: #6b7280;
            --ink-faint: #d1d5db;
            --surface: #ffffff;
            --surface-raised: #f9fafb;
            --surface-hover: #f3f4f6;
            --accent: #2563eb;
            --accent-light: #eff6ff;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --border: #e5e7eb;
            --sidebar-bg: #0f1117;
            --sidebar-text: #e2e8f0;
            --sidebar-muted: #64748b;
            --sidebar-hover: #1e2330;
            --sidebar-active: #1d4ed8;
            --sidebar-width: 240px;
            --sidebar-collapsed-width: 70px;
            --radius: 10px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-raised);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ── Sidebar ────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
            border-right: 1px solid #1e2330;
            transition: width 0.25s ease;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-toggle {
            position: absolute;
            top: 50%;
            right: -14px;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 60;
            transition: all 0.15s;
            color: var(--ink-muted);
        }

        .sidebar-toggle:hover {
            background: var(--surface-hover);
            color: var(--ink);
        }

        .sidebar-toggle svg {
            transition: transform 0.25s ease;
        }

        .sidebar.collapsed .sidebar-toggle svg {
            transform: rotate(180deg);
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid #1e2330;
            transition: padding 0.25s ease;
        }

        .sidebar.collapsed .sidebar-brand {
            padding: 24px 14px 20px;
        }

        .sidebar-brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-icon {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg { color: white; }

        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 17px;
            color: #f1f5f9;
            letter-spacing: -0.02em;
            line-height: 1;
            transition: opacity 0.25s ease;
        }

        .brand-tagline {
            font-size: 10px;
            color: var(--sidebar-muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 2px;
            transition: opacity 0.25s ease;
        }

        .sidebar.collapsed .brand-name,
        .sidebar.collapsed .brand-tagline {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--sidebar-muted);
            padding: 8px 8px 4px;
            margin-top: 8px;
            white-space: nowrap;
            transition: opacity 0.25s ease;
        }

        .sidebar.collapsed .nav-section-label {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 7px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 400;
            transition: background 0.15s, color 0.15s, padding 0.25s ease;
            opacity: 0.75;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 9px;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            opacity: 1;
        }

        .nav-link.active {
            background: rgba(37, 99, 235, 0.2);
            color: #93c5fd;
            opacity: 1;
            font-weight: 500;
        }

        .nav-link svg { flex-shrink: 0; opacity: 0.7; }
        .nav-link.active svg { opacity: 1; }

        .nav-link-text {
            transition: opacity 0.25s ease;
        }

        .sidebar.collapsed .nav-link-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .nav-link-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 600;
            background: var(--sidebar-hover);
            color: var(--sidebar-muted);
            padding: 1px 6px;
            border-radius: 20px;
            transition: opacity 0.25s ease;
        }

        .sidebar.collapsed .nav-link-badge {
            display: none;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid #1e2330;
            transition: padding 0.25s ease;
        }

        .sidebar.collapsed .sidebar-footer {
            padding: 16px 10px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 7px;
            color: #f87171;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 400;
            transition: background 0.15s, padding 0.25s ease;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            opacity: 0.75;
        }

        .logout-btn:hover { background: rgba(220, 38, 38, 0.1); opacity: 1; }

        .logout-btn-text {
            transition: opacity 0.25s ease;
        }

        .sidebar.collapsed .logout-btn-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* ── Main Content ───────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s ease;
        }

        .sidebar.collapsed ~ .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 12px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--ink-muted);
        }

        .topbar-breadcrumb a {
            color: var(--ink-muted);
            text-decoration: none;
            transition: color 0.15s;
        }
        .topbar-breadcrumb a:hover { color: var(--ink); }

        .topbar-breadcrumb-sep { opacity: 0.4; }
        .topbar-breadcrumb-current { color: var(--ink); font-weight: 500; }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-new-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent);
            color: white;
            border: none;
            padding: 7px 14px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, transform 0.1s;
        }
        .topbar-new-btn:hover { background: #1d4ed8; transform: translateY(-1px); }
        .topbar-new-btn:active { transform: translateY(0); }

        .avatar-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: 100px;
            border: 1px solid var(--border);
            background: var(--surface);
            cursor: pointer;
            transition: background 0.15s;
        }
        .avatar-chip:hover { background: var(--surface-hover); }

        .avatar-img {
            width: 26px; height: 26px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
        }

        /* ── Page Content ───────────────────────────── */
        .page-content {
            flex: 1;
            padding: 32px;
            max-width: 1100px;
            width: 100%;
        }

        /* ── Flash Messages ─────────────────────────── */
        .flash-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--success-light);
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 24px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Utilities ──────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; transform: translateY(-1px); box-shadow: var(--shadow-md); }

        .btn-ghost {
            background: transparent;
            color: var(--ink);
            border-color: var(--border);
        }
        .btn-ghost:hover { background: var(--surface-hover); }

        .btn-danger {
            background: transparent;
            color: var(--danger);
            border-color: #fecaca;
        }
        .btn-danger:hover { background: var(--danger-light); border-color: #fca5a5; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            display: block;
            width: 100%;
            padding: 9px 12px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 7px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .form-input::placeholder { color: #9ca3af; }

        .form-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 5px;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle sidebar">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>

        <div class="sidebar-brand">
            <a href="{{ url('/') }}" class="sidebar-brand-logo">
                <div class="brand-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <div class="brand-name">UserOS</div>
                    <div class="brand-tagline">Admin Portal</div>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Main</span>

            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                <span class="nav-link-text">Overview</span>
            </a>

            <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.index') || request()->routeIs('user.edit') || request()->routeIs('user.show') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span class="nav-link-text">Users</span>
            </a>

            <a href="{{ route('user.create') }}" class="nav-link {{ request()->routeIs('user.create') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                <span class="nav-link-text">Add User</span>
            </a>

            <span class="nav-section-label">Tools</span>

            <a href="#" class="nav-link" style="pointer-events: none;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                <span class="nav-link-text">Analytics</span>
                <span class="nav-link-badge">Soon</span>
            </a>

            <a href="#" class="nav-link" style="pointer-events: none;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                </svg>
                <span class="nav-link-text">Settings</span>
                <span class="nav-link-badge">Soon</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" style="display:contents">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span class="logout-btn-text">Sign out</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-breadcrumb">
                <a href="{{ url('/') }}">UserOS</a>
                <span class="topbar-breadcrumb-sep">/</span>
                <span class="topbar-breadcrumb-current">@yield('title', 'Dashboard')</span>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('user.create') }}" class="topbar-new-btn">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    New User
                </a>
                <div class="avatar-chip">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff" alt="Admin" class="avatar-img">
                    <span class="avatar-name">Admin</span>
                </div>
            </div>
        </header>

        <main class="page-content">
            @if (session('success'))
                <div class="flash-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Initialize sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                document.body.classList.add('sidebar-collapsed');
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');

            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        }
    </script>

</body>
</html>
