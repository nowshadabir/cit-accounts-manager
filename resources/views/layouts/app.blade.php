<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} &mdash; Accounts Management CIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
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
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* App Layout */
        .app-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Minimal Light Sidebar */
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
            z-index: 50;
            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            color: var(--gray-900);
            font-weight: 700;
            font-size: 0.975rem;
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            color: var(--gray-500);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .sidebar-nav {
            padding: 1.25rem 0.75rem;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .nav-section-label {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--gray-400);
            padding: 0.5rem 0.75rem 0.25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5625rem 0.75rem;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-item:hover {
            color: var(--sidebar-text-hover);
            background-color: var(--sidebar-item-hover);
        }

        .nav-item.active {
            color: var(--sidebar-item-active-text);
            background-color: var(--sidebar-item-active-bg);
            font-weight: 600;
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
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--sidebar-border);
            background-color: #ffffff;
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
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 700;
            font-size: 0.8125rem;
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
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: capitalize;
        }

        .user-role-tag {
            display: inline-block;
            color: var(--primary);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
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

        /* Main Area */
        .app-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* Topbar Header */
        .app-topbar {
            height: 60px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .mobile-sidebar-toggle {
            display: none;
            background: none;
            border: 1px solid var(--gray-200);
            padding: 0.35rem;
            border-radius: 6px;
            color: var(--gray-700);
            cursor: pointer;
        }

        .page-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--gray-900);
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
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            border: 1px solid var(--gray-200);
        }

        /* Content Area */
        .app-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(16, 24, 40, 0.4);
            backdrop-filter: blur(2px);
            z-index: 45;
        }

        @media (max-width: 900px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .app-main {
                margin-left: 0;
            }

            .mobile-sidebar-toggle {
                display: flex;
            }

            .app-topbar {
                padding: 0 1.25rem;
            }

            .app-content {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Mobile Backdrop Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Body -->
        <div class="app-main">
            <!-- Topbar -->
            <header class="app-topbar">
                <div class="topbar-left">
                    <button type="button" class="mobile-sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"/>
                            <line x1="4" x2="20" y1="6" x2="6"/>
                            <line x1="4" x2="20" y1="18" y2="18"/>
                        </svg>
                    </button>
                    <h1 class="page-title">{{ $header ?? $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="topbar-right">
                    <span class="topbar-badge">
                        {{ now()->format('D, d M Y') }}
                    </span>
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="app-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('open');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            });
        }
    </script>
</body>
</html>
