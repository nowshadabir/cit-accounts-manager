<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    
    <!-- PWA & Mobile Web App Meta -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="CIT Accounts">
    <meta name="application-name" content="CIT Accounts">
    <meta name="theme-color" content="#2563eb">
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-navbutton-color" content="#2563eb">

    <title>{{ $title ?? 'Dashboard' }} &mdash; CIT Accounts</title>

    <!-- Manifest & App Icons -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pwa.css') }}">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-100: #dbeafe;
            --sidebar-bg: #ffffff;
            --sidebar-border: #eaecf0;
            --sidebar-text: #475467;
            --sidebar-text-hover: #101828;
            --sidebar-item-hover: #f8fafc;
            --sidebar-item-active-bg: #eff6ff;
            --sidebar-item-active-text: #1d4ed8;
            --gray-50: #f9fafb;
            --gray-100: #f2f4f7;
            --gray-200: #eaecf0;
            --gray-300: #d0d5dd;
            --gray-400: #98a2b3;
            --gray-500: #667085;
            --gray-600: #475467;
            --gray-700: #344054;
            --gray-800: #1d2939;
            --gray-900: #101828;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 72px;
            --topbar-height: 60px;
            --bottom-nav-height: 64px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --ease-spring: cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            min-height: 100vh;
            min-height: -webkit-fill-available;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            touch-action: manipulation;
        }

        /* App Layout Container */
        .app-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }

        /* Desktop & Mobile Sidebar */
        .app-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 90;
            transition: width 0.28s var(--ease-spring), transform 0.28s var(--ease-spring);
            will-change: transform, width;
        }

        /* Collapsed Sidebar State (Desktop) */
        body.sidebar-collapsed .app-sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .brand-info,
        body.sidebar-collapsed .nav-label,
        body.sidebar-collapsed .nav-section-label,
        body.sidebar-collapsed .user-details,
        body.sidebar-collapsed .sidebar-collapse-text {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 1.25rem 0.5rem;
        }

        body.sidebar-collapsed .nav-item {
            justify-content: center;
            padding: 0.65rem 0;
        }

        body.sidebar-collapsed .user-card {
            justify-content: center;
            padding: 0.5rem 0;
            background: none;
            border: none;
        }

        body.sidebar-collapsed .sidebar-logout-btn {
            display: none;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: var(--topbar-height);
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 4px 8px -2px rgba(37, 99, 235, 0.35);
        }

        .brand-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
        }

        .brand-title {
            color: var(--gray-900);
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            color: var(--gray-500);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .mobile-close-btn {
            display: none;
            background: var(--gray-100);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            color: var(--gray-700);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .mobile-close-btn:active {
            transform: scale(0.92);
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-section-label {
            font-size: 0.6875rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--gray-400);
            padding: 0.65rem 0.75rem 0.35rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: var(--radius-md);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.18s var(--ease-spring);
            position: relative;
            user-select: none;
        }

        .nav-item:hover {
            color: var(--sidebar-text-hover);
            background-color: var(--sidebar-item-hover);
        }

        .nav-item.active {
            color: var(--sidebar-item-active-text);
            background-color: var(--sidebar-item-active-bg);
            font-weight: 700;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            bottom: 25%;
            width: 3.5px;
            background-color: var(--primary);
            border-radius: 0 4px 4px 0;
        }

        .nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: inherit;
            flex-shrink: 0;
        }

        .nav-label {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 0.875rem 0.75rem;
            border-top: 1px solid var(--sidebar-border);
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .sidebar-collapse-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            height: 34px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-600);
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .sidebar-collapse-toggle:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.625rem;
            border-radius: 8px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #3730a3;
            font-weight: 800;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-fullname {
            display: block;
            color: var(--gray-900);
            font-size: 0.8125rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: capitalize;
        }

        .user-email-text {
            display: block;
            font-size: 0.7rem;
            color: var(--gray-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-logout-btn {
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 0.35rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .sidebar-logout-btn:hover {
            color: #ef4444;
            background: #fee2e2;
        }

        /* Main App Area */
        .app-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            transition: margin-left 0.28s var(--ease-spring);
        }

        body.sidebar-collapsed .app-main {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Top Bar Header */
        .app-topbar {
            height: var(--topbar-height);
            background-color: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.02);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            color: var(--gray-700);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .topbar-icon-btn:active {
            transform: scale(0.92);
            background: var(--gray-100);
        }

        .desktop-collapse-btn {
            display: inline-flex;
        }

        .mobile-drawer-toggle {
            display: none;
        }

        .page-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.02em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-badge {
            background: var(--gray-50);
            color: var(--gray-600);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .topbar-profile-link {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8125rem;
            text-decoration: none;
            border: 1px solid var(--primary-100);
            transition: all 0.15s ease;
        }

        .topbar-profile-link:hover {
            background: var(--primary);
            color: #ffffff;
        }

        /* Page Content Area */
        .app-content {
            flex: 1;
            padding: 2rem;
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
        }

        /* Mobile Drawer Backdrop Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(16, 24, 40, 0.45);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 85;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .sidebar-overlay.open {
            display: block;
            opacity: 1;
        }

        /* Mobile App Bottom Navigation Bar */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid var(--gray-200);
            z-index: 60;
            padding-bottom: env(safe-area-inset-bottom, 0);
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.04);
            justify-content: space-around;
            align-items: center;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            height: 100%;
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.6875rem;
            font-weight: 600;
            transition: all 0.15s ease;
            position: relative;
            padding: 4px 0;
        }

        .bottom-nav-item:active {
            transform: scale(0.92);
        }

        .bottom-nav-item.active {
            color: var(--primary);
            font-weight: 700;
        }

        .bottom-nav-item.active .bottom-nav-icon {
            transform: translateY(-1px);
        }

        .bottom-nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.15s ease;
        }

        /* Touch target improvements */
        button, a, input, select {
            touch-action: manipulation;
        }

        /* Mobile Responsive Media Queries */
        @media (max-width: 991px) {
            .desktop-collapse-btn {
                display: none;
            }

            .mobile-drawer-toggle {
                display: inline-flex;
            }

            .app-sidebar {
                transform: translateX(-100%);
                width: 280px !important;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            }

            .app-sidebar.open {
                transform: translateX(0);
            }

            .mobile-close-btn {
                display: inline-flex;
            }

            .app-main {
                margin-left: 0 !important;
            }

            .app-topbar {
                padding: 0 1rem;
            }

            .app-content {
                padding: 1.25rem 1rem;
                padding-bottom: calc(var(--bottom-nav-height) + 1.5rem);
            }

            .mobile-bottom-nav {
                display: flex;
            }

            .topbar-badge {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .app-content {
                padding: 1rem 0.75rem;
                padding-bottom: calc(var(--bottom-nav-height) + 2rem);
            }

            .page-title {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Navigation -->
        <x-sidebar />

        <!-- Mobile Drawer Backdrop -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Body -->
        <div class="app-main">
            <!-- Topbar Header -->
            <header class="app-topbar">
                <div class="topbar-left">
                    <!-- Mobile Hamburger Toggle -->
                    <button type="button" class="topbar-icon-btn mobile-drawer-toggle" id="mobileSidebarToggle" aria-label="Open Navigation Menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>

                    <!-- Desktop Sidebar Collapse Toggle -->
                    <button type="button" class="topbar-icon-btn desktop-collapse-btn" id="desktopSidebarToggle" title="Toggle Sidebar Width">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <line x1="9" y1="3" x2="9" y2="21"/>
                        </svg>
                    </button>

                    <h1 class="page-title">{{ $header ?? $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="topbar-right">
                    <span class="topbar-badge">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ now()->format('D, d M Y') }}
                    </span>

                    @if(Auth::check())
                        <a href="{{ route('profile.show') }}" class="topbar-profile-link" title="My Profile">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </a>
                    @endif
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="app-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Native Bottom Navigation Bar -->
    <nav class="mobile-bottom-nav" aria-label="Mobile Navigation">
        <!-- 1. Dashboard -->
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="bottom-nav-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
            </span>
            <span>Home</span>
        </a>

        <!-- 2. Entry Form -->
        <a href="{{ route('entries.index') }}" class="bottom-nav-item {{ request()->routeIs('entries.*') ? 'active' : '' }}">
            <span class="bottom-nav-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                    <path d="m15 5 3 3"/>
                </svg>
            </span>
            <span>Entries</span>
        </a>

        <!-- 3. Reports -->
        <a href="{{ route('reports.index') }}" class="bottom-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="bottom-nav-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </span>
            <span>Reports</span>
        </a>

        <!-- 4. Profile -->
        <a href="{{ route('profile.show') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="bottom-nav-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="10" r="3"/>
                    <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/>
                </svg>
            </span>
            <span>Profile</span>
        </a>

        <!-- 5. More Menu Drawer Toggle -->
        <button type="button" class="bottom-nav-item" id="bottomNavMenuBtn" style="background: none; border: none;">
            <span class="bottom-nav-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="19" cy="12" r="1"/>
                    <circle cx="5" cy="12" r="1"/>
                </svg>
            </span>
            <span>Menu</span>
        </button>
    </nav>

    <script>
        // Sidebar Collapsible & Mobile Drawer Controller
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mobileToggle = document.getElementById('mobileSidebarToggle');
        const desktopToggle = document.getElementById('desktopSidebarToggle');
        const mobileCloseBtn = document.getElementById('sidebarCloseBtn');
        const bottomNavMenuBtn = document.getElementById('bottomNavMenuBtn');

        // Load and apply saved desktop collapsed preference
        if (localStorage.getItem('cit_sidebar_collapsed') === 'true' && window.innerWidth >= 992) {
            document.body.classList.add('sidebar-collapsed');
        }

        function toggleDesktopSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('cit_sidebar_collapsed', document.body.classList.contains('sidebar-collapsed'));
        }

        function openMobileDrawer() {
            if (sidebar && overlay) {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileDrawer() {
            if (sidebar && overlay) {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        if (desktopToggle) {
            desktopToggle.addEventListener('click', toggleDesktopSidebar);
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', openMobileDrawer);
        }

        if (bottomNavMenuBtn) {
            bottomNavMenuBtn.addEventListener('click', openMobileDrawer);
        }

        if (mobileCloseBtn) {
            mobileCloseBtn.addEventListener('click', closeMobileDrawer);
        }

        if (overlay) {
            overlay.addEventListener('click', closeMobileDrawer);
        }

        // Close mobile drawer when pressing Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMobileDrawer();
            }
        });
    </script>

    <!-- PWA Client Manager -->
    <script src="{{ asset('js/pwa.js') }}" defer></script>
</body>
</html>
