<x-layouts.app title="Dashboard">
    <style>
        .welcome-hero {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: var(--radius-xl, 18px);
            padding: 2rem 1.75rem;
            color: #ffffff;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .welcome-text h2 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 0.35rem;
        }

        .welcome-text p {
            font-size: 0.9rem;
            opacity: 0.92;
            max-width: 600px;
            line-height: 1.45;
        }

        .role-pill {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg, 14px);
            padding: 1.25rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
            border-color: var(--gray-300);
        }

        .stat-card:active {
            transform: scale(0.98);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            min-width: 0;
        }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
        }

        .stat-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-blue {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .icon-emerald {
            background-color: #ecfdf5;
            color: #059669;
        }

        .icon-purple {
            background-color: #f3e8ff;
            color: #9333ea;
        }

        .icon-amber {
            background-color: #fffbeb;
            color: #d97706;
        }

        .quick-links-section {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl, 18px);
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .section-heading {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 1.25rem;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        .quick-link-card {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md, 12px);
            padding: 1.25rem;
            text-decoration: none;
            color: var(--gray-800);
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .quick-link-card:hover {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
        }

        .quick-link-card:active {
            transform: scale(0.98);
        }

        .ql-title {
            font-weight: 700;
            font-size: 0.9375rem;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ql-desc {
            font-size: 0.8125rem;
            color: var(--gray-500);
            line-height: 1.45;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1100px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .quick-links-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 560px) {
            .welcome-hero {
                padding: 1.25rem 1rem;
                border-radius: 14px;
            }

            .welcome-text h2 {
                font-size: 1.25rem;
            }

            .welcome-text p {
                font-size: 0.8125rem;
            }

            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
                margin-bottom: 1.25rem;
            }

            .stat-card {
                padding: 1rem 0.875rem;
            }

            .stat-value {
                font-size: 1.15rem;
            }

            .stat-icon-wrapper {
                width: 36px;
                height: 36px;
                border-radius: 10px;
            }

            .quick-links-section {
                padding: 1.15rem 1rem;
            }

            .quick-links-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }

        @media (max-width: 420px) {
            .stat-card {
                padding: 0.75rem 0.65rem;
            }

            .stat-label {
                font-size: 0.625rem;
            }

            .stat-value {
                font-size: 1.05rem;
            }

            .stat-icon-wrapper {
                width: 30px;
                height: 30px;
                border-radius: 8px;
            }

            .stat-icon-wrapper svg {
                width: 16px;
                height: 16px;
            }
        }
    </style>

    <!-- Welcome Hero -->
    <div class="welcome-hero">
        <div class="welcome-text">
            <h2>Welcome back, {{ Auth::user()->name }}!</h2>
            <p>You have access to CIT Accounts Management as <strong>{{ Auth::user()->email }}</strong>.</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">System Status</div>
                <div class="stat-value" style="color: #059669;">Online</div>
            </div>
            <div class="stat-icon-wrapper icon-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Entry Form</div>
                <div class="stat-value">Ready</div>
            </div>
            <div class="stat-icon-wrapper icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Report Export</div>
                <div class="stat-value">Active</div>
            </div>
            <div class="stat-icon-wrapper icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Account Status</div>
                <div class="stat-value" style="color: #059669;">Verified</div>
            </div>
            <div class="stat-icon-wrapper icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 12 12 15 16 10"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Shortcuts -->
    <div class="quick-links-section">
        <h3 class="section-heading">Quick Shortcuts</h3>
        <div class="quick-links-grid">
            <a href="{{ route('entries.index') }}" class="quick-link-card">
                <div class="ql-title">Entry Form &rarr;</div>
                <div class="ql-desc">Record transactions, vouchers, and accounts ledger entries.</div>
            </a>
            <a href="{{ route('reports.index') }}" class="quick-link-card">
                <div class="ql-title">Report Export &rarr;</div>
                <div class="ql-desc">Generate statements, export PDF/Excel accounts summaries.</div>
            </a>
            @if(Auth::check() && Auth::user()->role === 'super admin')
            <a href="{{ route('users.index') }}" class="quick-link-card">
                <div class="ql-title">Users Management &rarr;</div>
                <div class="ql-desc">Manage system users, credentials, and accounts.</div>
            </a>
            @endif
            <a href="{{ route('settings.index') }}" class="quick-link-card">
                <div class="ql-title">System Settings &rarr;</div>
                <div class="ql-desc">Configure Gmail SMTP, Namecheap FTP storage, and system parameters.</div>
            </a>
        </div>
    </div>
</x-layouts.app>
