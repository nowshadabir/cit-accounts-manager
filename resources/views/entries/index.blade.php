<x-layouts.app title="Treasury & Financial Entries">
    <style>
        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .header-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.2rem;
        }

        .action-btns-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn {
            height: 38px;
            padding: 0 1rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: all 0.15s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-emerald {
            background-color: #059669;
            border-color: #059669;
            color: #ffffff;
        }

        .btn-emerald:hover {
            background-color: #047857;
        }

        .btn-indigo {
            background-color: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
        }

        .btn-indigo:hover {
            background-color: #4338ca;
        }

        .btn-rose {
            background-color: #e11d48;
            border-color: #e11d48;
            color: #ffffff;
        }

        .btn-rose:hover {
            background-color: #be123c;
        }

        .btn-secondary {
            background-color: #ffffff;
            border-color: var(--gray-300);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background-color: var(--gray-50);
            color: var(--gray-900);
        }

        .btn-danger-ghost {
            background: none;
            border: 1px solid transparent;
            color: #ef4444;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-danger-ghost:hover {
            background-color: #fef2f2;
            border-color: #fecaca;
        }

        /* Alerts */
        .alert-box {
            padding: 0.75rem 1.125rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Treasury Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.125rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.02);
            transition: all 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 24, 40, 0.04);
            border-color: var(--gray-300);
        }

        .stat-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-amount {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--gray-900);
            font-variant-numeric: tabular-nums;
        }

        .stat-sub {
            font-size: 0.72rem;
            color: var(--gray-400);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Tab Navigation Bar */
        .tabs-header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .nav-tabs {
            display: flex;
            gap: 0.25rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-500);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .nav-tab-btn:hover {
            color: var(--gray-900);
        }

        .nav-tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            font-weight: 700;
        }

        .nav-tab-badge {
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 0.125rem 0.4rem;
            border-radius: 9999px;
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .nav-tab-btn.active .nav-tab-badge {
            background: #eff6ff;
            color: var(--primary);
        }

        /* Filter Controls */
        .tab-filter-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-select {
            height: 34px;
            padding: 0 0.75rem;
            border-radius: 6px;
            border: 1px solid var(--gray-300);
            font-size: 0.75rem;
            background: #ffffff;
            color: var(--gray-700);
            outline: none;
            font-family: inherit;
        }

        .filter-select:focus {
            border-color: var(--primary);
        }

        /* Tab Content Panes */
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* Card Container */
        .content-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.04);
            overflow: hidden;
        }

        .card-header-bar {
            padding: 1rem 1.25rem;
            background: #ffffff;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .card-header-title {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .card-header-desc {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.1rem;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8125rem;
        }

        .data-table th {
            background: #f8fafc;
            padding: 0.65rem 1.125rem;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gray-500);
            border-bottom: 1px solid var(--gray-200);
        }

        .data-table td {
            padding: 0.75rem 1.125rem;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background-color: #fafbfc;
        }

        /* Badges */
        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .doc-link-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.55rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 0.72rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .doc-link-badge:hover {
            background: #dcfce7;
            border-color: #86efac;
        }

        /* User Ledger Grid */
        .ledger-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1rem;
            padding: 1.25rem;
        }

        .user-ledger-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 1.125rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: all 0.15s ease;
        }

        .user-ledger-card:hover {
            border-color: var(--gray-300);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .user-ledger-card.highlight-reimburse {
            border-color: #fca5a5;
            background: #fffafa;
        }

        .user-ledger-card.highlight-holding {
            border-color: #cbd5e1;
            background: #ffffff;
        }

        .ledger-user-meta {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .user-avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #eff6ff;
            color: var(--primary);
            font-weight: 700;
            font-size: 0.8125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbeafe;
        }

        .ledger-numbers {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            padding: 0.65rem;
        }

        .num-label {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
        }

        .num-val {
            font-size: 1.0625rem;
            font-weight: 800;
            margin-top: 0.1rem;
        }

        .status-badge-box {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .status-reimburse {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .status-holding {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        .status-settled {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        /* Modal Structure */
        .entry-modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(3px);
            z-index: 1050;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .entry-modal-backdrop.open {
            display: flex;
        }

        .entry-modal {
            background: #ffffff;
            border-radius: 12px;
            width: 100%;
            max-width: 540px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .modal-header-bar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .modal-body-content {
            padding: 1.25rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.35rem;
        }

        .form-control {
            width: 100%;
            height: 38px;
            padding: 0 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.8125rem;
            color: var(--gray-900);
            background: #ffffff;
            outline: none;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea.form-control {
            height: auto;
            min-height: 68px;
            padding: 0.5rem 0.75rem;
        }

        .pagination-container {
            padding: 0.875rem 1.25rem;
            border-top: 1px solid var(--gray-200);
            background: #ffffff;
        }

        .empty-state-box {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--gray-500);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Tablet & Mobile Grid Breakpoints */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.875rem;
                margin-bottom: 1.25rem;
            }

            .header-title {
                font-size: 1.25rem;
            }

            .action-btns-group {
                width: 100%;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0.5rem;
            }

            .action-btns-group .btn {
                width: 100%;
                padding: 0 0.5rem;
                font-size: 0.75rem;
                height: 38px;
            }

            .action-btns-group .btn span {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .stat-card {
                padding: 0.875rem;
            }

            .stat-amount {
                font-size: 1.1rem;
            }

            .tabs-header-wrapper {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 4px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                gap: 0.5rem;
            }

            .tabs-header-wrapper::-webkit-scrollbar {
                display: none;
            }

            .nav-tabs {
                flex-shrink: 0;
            }

            .tab-filter-bar {
                flex-shrink: 0;
            }

            .nav-tab-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
                white-space: nowrap;
            }

            .ledger-grid {
                grid-template-columns: 1fr;
                padding: 0.875rem;
            }

            .form-row-2 {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .form-control {
                font-size: 16px; /* Prevents iOS Safari auto-zoom */
                height: 42px;
            }

            /* Native Bottom Sheet Modal on Mobile */
            .entry-modal-backdrop {
                padding: 0;
                align-items: flex-end;
            }

            .entry-modal {
                max-width: 100%;
                border-radius: 20px 20px 0 0;
                max-height: 88vh;
                animation: slideUpModal 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .modal-header-bar {
                position: sticky;
                top: 0;
                background: #ffffff;
                z-index: 10;
                padding: 1rem 1.25rem;
            }

            .modal-header-bar::before {
                content: '';
                position: absolute;
                top: 6px;
                left: 50%;
                transform: translateX(-50%);
                width: 36px;
                height: 4px;
                background: var(--gray-300);
                border-radius: 4px;
            }
        }

        @keyframes slideUpModal {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .action-btns-group {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.625rem;
            }

            .stat-card {
                padding: 0.75rem 0.65rem;
            }

            .stat-label {
                font-size: 0.625rem;
            }

            .stat-amount {
                font-size: 1.05rem;
            }

            .stat-sub {
                font-size: 0.65rem;
            }
        }
    </style>

    <!-- Top Page Header -->
    <div class="page-header">
        <div>
            <h1 class="header-title">Treasury & Financial Entries</h1>
            <p class="header-desc">Manage capital funds, member advances, and documented expense vouchers.</p>
        </div>
        <div class="action-btns-group">
            @if(auth()->user()->canEditAccounts())
                <button type="button" class="btn btn-rose" onclick="openModal('expenseModal')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Record Expense</span>
                </button>

                <button type="button" class="btn btn-indigo" onclick="openModal('disbursalModal')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                    <span>Disburse Advance</span>
                </button>

                <button type="button" class="btn btn-emerald" onclick="openModal('depositModal')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Add Deposit</span>
                </button>
            @else
                <div style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 0.85rem; background: #f8fafc; border: 1px solid var(--gray-200); border-radius: 8px; color: var(--gray-600); font-size: 0.8125rem; font-weight: 600;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>View-Only Access (Read Only)</span>
                </div>
            @endif
        </div>
    </div>

    @if (session('status'))
        <div class="alert-box alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-box alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- 1. Key Metrics Strip -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total Capital Raised</span>
            <span class="stat-amount" style="color: #059669;">৳ {{ number_format($totalFundRaised, 2) }}</span>
            <span class="stat-sub">From all member deposits</span>
        </div>

        <div class="stat-card">
            <span class="stat-label">Advances Disbursed</span>
            <span class="stat-amount" style="color: #4f46e5;">৳ {{ number_format($totalDisbursed, 2) }}</span>
            <span class="stat-sub">Transferred to members</span>
        </div>

        <div class="stat-card">
            <span class="stat-label">Documented Expenses</span>
            <span class="stat-amount" style="color: #e11d48;">৳ {{ number_format($totalExpenses, 2) }}</span>
            <span class="stat-sub">Spent across categories</span>
        </div>

        <div class="stat-card" style="background: #f8fbff; border-color: #bfdbfe;">
            <span class="stat-label" style="color: #1d4ed8;">Available Treasury Balance</span>
            <span class="stat-amount" style="color: #1d4ed8;">৳ {{ number_format($treasuryBalance, 2) }}</span>
            <span class="stat-sub">Unallocated central balance</span>
        </div>
    </div>

    <!-- 2. Segmented Tabs Header + Global Member Filter -->
    <div class="tabs-header-wrapper">
        <ul class="nav-tabs" role="tablist">
            <li>
                <button type="button" class="nav-tab-btn {{ $activeTab === 'ledgers' ? 'active' : '' }}" onclick="switchTab('ledgers')" id="tab-btn-ledgers">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Member Settlements</span>
                    <span class="nav-tab-badge">{{ count($userLedgers) }}</span>
                </button>
            </li>
            <li>
                <button type="button" class="nav-tab-btn {{ $activeTab === 'expenses' ? 'active' : '' }}" onclick="switchTab('expenses')" id="tab-btn-expenses">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="M7 15h0M2 9.5h20"></path>
                    </svg>
                    <span>Expense Vouchers</span>
                    <span class="nav-tab-badge">{{ $expenses->total() }}</span>
                </button>
            </li>
            <li>
                <button type="button" class="nav-tab-btn {{ $activeTab === 'disbursals' ? 'active' : '' }}" onclick="switchTab('disbursals')" id="tab-btn-disbursals">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    <span>Fund Advances</span>
                    <span class="nav-tab-badge">{{ $disbursals->total() }}</span>
                </button>
            </li>
            <li>
                <button type="button" class="nav-tab-btn {{ $activeTab === 'deposits' ? 'active' : '' }}" onclick="switchTab('deposits')" id="tab-btn-deposits">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v4M12 14v4M16 14v4"/>
                    </svg>
                    <span>Capital Deposits</span>
                    <span class="nav-tab-badge">{{ $deposits->total() }}</span>
                </button>
            </li>
        </ul>

        <!-- Member Filter Dropdown -->
        <form method="GET" action="{{ route('entries.index') }}" class="tab-filter-bar" id="filterForm">
            <input type="hidden" name="tab" id="filterTabInput" value="{{ $activeTab }}">
            <select name="user_id" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Filter by Member: All Members</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ $selectedUserId == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
            @if ($selectedUserId)
                <a href="{{ route('entries.index', ['tab' => $activeTab]) }}" class="btn-danger-ghost" title="Clear filter">Clear</a>
            @endif
        </form>
    </div>

    <!-- TAB 1: MEMBER SETTLEMENTS & LEDGERS -->
    <div class="tab-pane {{ $activeTab === 'ledgers' ? 'active' : '' }}" id="pane-ledgers">
        <div class="content-card">
            <div class="card-header-bar">
                <div>
                    <div class="card-header-title">Member Financial Balances & Settlements</div>
                    <div class="card-header-desc">Overview of advances held vs expenses incurred by each member. Automatically flags reimbursements.</div>
                </div>
            </div>

            <div class="ledger-grid">
                @forelse ($userLedgers as $ledger)
                    @if (!$selectedUserId || $selectedUserId == $ledger['user']->id)
                        @php
                            $highlightClass = match($ledger['status']) {
                                'reimburse' => 'highlight-reimburse',
                                'holding' => 'highlight-holding',
                                default => '',
                            };
                        @endphp
                        <div class="user-ledger-card {{ $highlightClass }}">
                            <div class="ledger-user-meta">
                                <div class="user-avatar-sm">
                                    {{ strtoupper(substr($ledger['user']->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; color: var(--gray-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: capitalize;">
                                        {{ $ledger['user']->name }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: var(--gray-500);">
                                        {{ $ledger['user']->email }}
                                    </div>
                                </div>
                            </div>

                            <div class="ledger-numbers">
                                <div>
                                    <div class="num-label">Advances Held</div>
                                    <div class="num-val" style="color: #4f46e5;">৳ {{ number_format($ledger['allocated'], 2) }}</div>
                                    <div style="font-size: 0.6875rem; color: var(--gray-400);">{{ $ledger['disbursal_count'] }} advances received</div>
                                </div>
                                <div>
                                    <div class="num-label">Total Spent</div>
                                    <div class="num-val" style="color: #e11d48;">৳ {{ number_format($ledger['spent'], 2) }}</div>
                                    <div style="font-size: 0.6875rem; color: var(--gray-400);">{{ $ledger['expense_count'] }} vouchers</div>
                                </div>
                            </div>

                            @if ($ledger['given_to_others'] > 0)
                                <div style="padding: 0.35rem 0.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 0.72rem; color: #166534; display: flex; align-items: center; justify-content: space-between;">
                                    <span>Transferred to others:</span>
                                    <strong>৳ {{ number_format($ledger['given_to_others'], 2) }}</strong>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            @if ($ledger['status'] === 'reimburse')
                                <div class="status-badge-box status-reimburse">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <div>
                                        <strong>Will receive ৳ {{ number_format($ledger['due_reimbursement'], 2) }}</strong> from institute.
                                    </div>
                                </div>
                            @elseif ($ledger['status'] === 'holding')
                                <div class="status-badge-box status-holding">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <div>
                                        <strong>Holding ৳ {{ number_format($ledger['unspent_fund'], 2) }}</strong> unspent institute fund.
                                    </div>
                                </div>
                            @else
                                <div class="status-badge-box status-settled">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    <div>Account fully balanced & settled.</div>
                                </div>
                            @endif
                        </div>
                    @endif
                @empty
                    <div class="empty-state-box" style="grid-column: 1 / -1;">No members found.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TAB 2: EXPENSE VOUCHERS -->
    <div class="tab-pane {{ $activeTab === 'expenses' ? 'active' : '' }}" id="pane-expenses">
        <div class="content-card">
            <div class="card-header-bar">
                <div>
                    <div class="card-header-title">Documented Expense Vouchers</div>
                    <div class="card-header-desc">All expenses incurred by members with Namecheap FTP stored receipts.</div>
                </div>
                @if(auth()->user()->canEditAccounts())
                    <button type="button" class="btn btn-rose" onclick="openModal('expenseModal')">
                        + Record Expense Voucher
                    </button>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Category</th>
                            <th>Voucher Description</th>
                            <th>Amount</th>
                            <th>Receipt / Doc</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td style="font-size: 0.75rem; color: var(--gray-500); white-space: nowrap;">
                                    {{ $expense->expense_date->format('d M, Y') }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $expense->user->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $expense->category }}</span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $expense->title }}</div>
                                    @if ($expense->description)
                                        <div style="font-size: 0.72rem; color: var(--gray-500);">{{ $expense->description }}</div>
                                    @endif
                                </td>
                                <td style="font-weight: 700; color: #e11d48; white-space: nowrap;">
                                    - ৳ {{ number_format($expense->amount, 2) }}
                                </td>
                                <td>
                                    @if ($expense->document_path)
                                        <a href="{{ route('documents.download', ['type' => 'expense', 'id' => $expense->id]) }}" title="Download attachment" class="doc-link-badge">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            <span>{{ Str::limit($expense->document_filename ?? 'Download File', 16) }}</span>
                                        </a>
                                    @else
                                        <span style="color: var(--gray-400); font-size: 0.72rem; font-style: italic;">No Doc</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if (auth()->user()->canEditAccounts())
                                        <form method="POST" action="{{ route('entries.expense.destroy', $expense) }}" onsubmit="return confirm('Delete this expense voucher?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-ghost">Delete</button>
                                        </form>
                                    @else
                                        <span style="color: var(--gray-400); font-size: 0.72rem; font-style: italic;">Read only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state-box">
                                        <p style="margin-bottom: 0.75rem;">No expense vouchers found.</p>
                                        @if(auth()->user()->canEditAccounts())
                                            <button type="button" class="btn btn-rose" onclick="openModal('expenseModal')">+ Record First Expense</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($expenses->hasPages())
                <div class="pagination-container">{{ $expenses->appends(['tab' => 'expenses'])->links() }}</div>
            @endif
        </div>
    </div>

    <!-- TAB 3: FUND DISBURSALS / ADVANCES -->
    <div class="tab-pane {{ $activeTab === 'disbursals' ? 'active' : '' }}" id="pane-disbursals">
        <div class="content-card">
            <div class="card-header-bar">
                <div>
                    <div class="card-header-title">Fund Advances & Transfers</div>
                    <div class="card-header-desc">Tracks who disbursed money to whom from the treasury or advance fund.</div>
                </div>
                @if(auth()->user()->canEditAccounts())
                    <button type="button" class="btn btn-indigo" onclick="openModal('disbursalModal')">
                        + Disburse Advance
                    </button>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Money Flow (Given By ➔ Recipient)</th>
                            <th>Purpose / Allocation</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($disbursals as $disbursal)
                            <tr>
                                <td style="font-size: 0.75rem; color: var(--gray-500); white-space: nowrap;">
                                    {{ $disbursal->disbursal_date->format('d M, Y') }}
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                        <div style="display: inline-flex; align-items: center; gap: 0.25rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.2rem 0.45rem;">
                                            <span style="font-size: 0.6875rem; text-transform: uppercase; font-weight: 700; color: #64748b;">From:</span>
                                            <span style="font-weight: 600; color: #1e293b; font-size: 0.75rem;">{{ $disbursal->givenBy->name ?? 'Treasury' }}</span>
                                        </div>
                                        
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>

                                        <div style="display: inline-flex; align-items: center; gap: 0.25rem; background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 6px; padding: 0.2rem 0.45rem;">
                                            <span style="font-size: 0.6875rem; text-transform: uppercase; font-weight: 700; color: #4338ca;">To:</span>
                                            <span style="font-weight: 700; color: #3730a3; font-size: 0.75rem;">{{ $disbursal->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    @if ($disbursal->approver && $disbursal->approver->id !== ($disbursal->given_by_user_id ?? 0))
                                        <div style="font-size: 0.6875rem; color: var(--gray-400); margin-top: 0.2rem;">Authorized by: {{ $disbursal->approver->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $disbursal->purpose }}</div>
                                    @if ($disbursal->notes)
                                        <div style="font-size: 0.72rem; color: var(--gray-500);">{{ $disbursal->notes }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.75rem; color: var(--gray-600);">{{ $disbursal->payment_method }}</span>
                                    @if ($disbursal->reference_no)
                                        <div style="font-size: 0.6875rem; color: var(--gray-400);">Ref: {{ $disbursal->reference_no }}</div>
                                    @endif
                                </td>
                                <td style="font-weight: 700; color: #4f46e5; white-space: nowrap;">
                                    ৳ {{ number_format($disbursal->amount, 2) }}
                                </td>
                                <td style="text-align: right;">
                                    @if (auth()->user()->canEditAccounts())
                                        <form method="POST" action="{{ route('entries.disbursal.destroy', $disbursal) }}" onsubmit="return confirm('Delete this fund disbursal?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-ghost">Delete</button>
                                        </form>
                                    @else
                                        <span style="color: var(--gray-400); font-size: 0.72rem; font-style: italic;">Read only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state-box">
                                        <p style="margin-bottom: 0.75rem;">No fund advances recorded.</p>
                                        @if(auth()->user()->canEditAccounts())
                                            <button type="button" class="btn btn-indigo" onclick="openModal('disbursalModal')">+ Disburse Advance</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($disbursals->hasPages())
                <div class="pagination-container">{{ $disbursals->appends(['tab' => 'disbursals'])->links() }}</div>
            @endif
        </div>
    </div>

    <!-- TAB 4: CAPITAL DEPOSITS -->
    <div class="tab-pane {{ $activeTab === 'deposits' ? 'active' : '' }}" id="pane-deposits">
        <div class="content-card">
            <div class="card-header-bar">
                <div>
                    <div class="card-header-title">Capital Deposits & Fund Inflow</div>
                    <div class="card-header-desc">Member capital contributions and bank deposits into central treasury.</div>
                </div>
                @if(auth()->user()->canEditAccounts())
                    <button type="button" class="btn btn-emerald" onclick="openModal('depositModal')">
                        + Add Deposit
                    </button>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Contributor</th>
                            <th>Method & Reference</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Deposit Slip</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deposits as $deposit)
                            <tr>
                                <td style="font-size: 0.75rem; color: var(--gray-500); white-space: nowrap;">
                                    {{ $deposit->deposit_date->format('d M, Y') }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $deposit->contributor_name }}</div>
                                    @if ($deposit->user)
                                        <div style="font-size: 0.6875rem; color: var(--primary);">Linked: {{ $deposit->user->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.75rem; color: var(--gray-600);">{{ $deposit->payment_method }}</span>
                                    @if ($deposit->reference_no)
                                        <div style="font-size: 0.6875rem; color: var(--gray-400);">Ref: {{ $deposit->reference_no }}</div>
                                    @endif
                                </td>
                                <td style="font-size: 0.75rem; color: var(--gray-600);">
                                    {{ $deposit->description ?? '—' }}
                                </td>
                                <td style="font-weight: 700; color: #059669; white-space: nowrap;">
                                    + ৳ {{ number_format($deposit->amount, 2) }}
                                </td>
                                <td>
                                    @if ($deposit->document_path)
                                        <a href="{{ route('documents.download', ['type' => 'deposit', 'id' => $deposit->id]) }}" title="Download attachment" class="doc-link-badge">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            <span>{{ Str::limit($deposit->document_filename ?? 'Download Doc', 16) }}</span>
                                        </a>
                                    @else
                                        <span style="color: var(--gray-400); font-size: 0.72rem; font-style: italic;">No Doc</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if (auth()->user()->canEditAccounts())
                                        <form method="POST" action="{{ route('entries.deposit.destroy', $deposit) }}" onsubmit="return confirm('Delete this fund deposit?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-ghost">Delete</button>
                                        </form>
                                    @else
                                        <span style="color: var(--gray-400); font-size: 0.72rem; font-style: italic;">Read only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state-box">
                                        <p style="margin-bottom: 0.75rem;">No capital deposits recorded.</p>
                                        @if(auth()->user()->canEditAccounts())
                                            <button type="button" class="btn btn-emerald" onclick="openModal('depositModal')">+ Add Deposit</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($deposits->hasPages())
                <div class="pagination-container">{{ $deposits->appends(['tab' => 'deposits'])->links() }}</div>
            @endif
        </div>
    </div>

    <!-- ==================== MODALS ==================== -->
    @if (auth()->user()->canEditAccounts())
        <!-- MODAL 1: RECORD EXPENSE VOUCHER -->
        <div class="entry-modal-backdrop" id="expenseModal">
            <div class="entry-modal">
                <div class="modal-header-bar">
                    <h3 class="modal-title">+ Record Expense Voucher</h3>
                    <button type="button" class="btn-danger-ghost" onclick="closeModal('expenseModal')">✕</button>
                </div>
                <form method="POST" action="{{ route('entries.expense.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body-content">
                        <div class="form-group">
                            <label for="exp_user_id" class="form-label">Expense By (Member)</label>
                            <select name="user_id" id="exp_user_id" class="form-control" required>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ $u->id === auth()->id() ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="exp_category" class="form-label">Expense Category</label>
                                <select name="category" id="exp_category" class="form-control" required>
                                    @foreach ($categories as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exp_amount" class="form-label">Amount (BDT)</label>
                                <input type="number" step="0.01" name="amount" id="exp_amount" class="form-control" placeholder="e.g. 2500" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="exp_date" class="form-label">Expense Date</label>
                                <input type="date" name="expense_date" id="exp_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="exp_title" class="form-label">Voucher Title</label>
                                <input type="text" name="title" id="exp_title" class="form-control" placeholder="e.g. Office Stationery" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exp_description" class="form-label">Description / Remarks</label>
                            <textarea name="description" id="exp_description" class="form-control" placeholder="Items breakdown or notes..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="exp_document" class="form-label">Upload Receipt / Bill (PDF or Image)</label>
                            <input type="file" name="document" id="exp_document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" style="padding-top: 0.35rem;">
                            <span style="font-size: 0.6875rem; color: var(--gray-400); margin-top: 0.2rem; display: block;">Saved to Namecheap FTP storage.</span>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem;">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('expenseModal')">Cancel</button>
                            <button type="submit" class="btn btn-rose">Save Expense Voucher</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: FUND DISBURSAL / ADVANCE -->
        <div class="entry-modal-backdrop" id="disbursalModal">
            <div class="entry-modal">
                <div class="modal-header-bar">
                    <h3 class="modal-title">+ Disburse Fund Advance</h3>
                    <button type="button" class="btn-danger-ghost" onclick="closeModal('disbursalModal')">✕</button>
                </div>
                <form method="POST" action="{{ route('entries.disbursal.store') }}">
                    @csrf
                    <div class="modal-body-content">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem; margin-bottom: 1rem;">
                            <div style="font-size: 0.6875rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem;">
                                Transfer Flow (Who is giving to whom)
                            </div>
                            <div class="form-row-2">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="dis_given_by_user_id" class="form-label">Given By (Sender / Payer)</label>
                                    <select name="given_by_user_id" id="dis_given_by_user_id" class="form-control" required>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}" {{ $u->id === auth()->id() ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="dis_user_id" class="form-label" style="color: #4338ca; font-weight: 700;">Given To (Recipient)</label>
                                    <select name="user_id" id="dis_user_id" class="form-control" required>
                                        <option value="">-- Choose Recipient --</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="dis_amount" class="form-label">Amount (BDT)</label>
                                <input type="number" step="0.01" name="amount" id="dis_amount" class="form-control" placeholder="e.g. 10000" required>
                            </div>
                            <div class="form-group">
                                <label for="dis_date" class="form-label">Disbursal Date</label>
                                <input type="date" name="disbursal_date" id="dis_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dis_purpose" class="form-label">Purpose / Allocation Heading</label>
                            <input type="text" name="purpose" id="dis_purpose" class="form-control" placeholder="e.g. Advance for office table & supplies" required>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="dis_payment_method" class="form-label">Disbursal Method</label>
                                <select name="payment_method" id="dis_payment_method" class="form-control" required>
                                    @foreach ($paymentMethods as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dis_reference_no" class="form-label">Transaction Reference</label>
                                <input type="text" name="reference_no" id="dis_reference_no" class="form-control" placeholder="e.g. Cash voucher #42">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dis_notes" class="form-label">Additional Remarks</label>
                            <textarea name="notes" id="dis_notes" class="form-control" placeholder="Internal remarks..."></textarea>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem;">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('disbursalModal')">Cancel</button>
                            <button type="submit" class="btn btn-indigo">Confirm Disbursal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 3: RECORD CAPITAL DEPOSIT -->
        <div class="entry-modal-backdrop" id="depositModal">
            <div class="entry-modal">
                <div class="modal-header-bar">
                    <h3 class="modal-title">+ Add Capital Deposit</h3>
                    <button type="button" class="btn-danger-ghost" onclick="closeModal('depositModal')">✕</button>
                </div>
                <form method="POST" action="{{ route('entries.deposit.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body-content">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="dep_contributor" class="form-label">Contributor Name</label>
                                <input type="text" name="contributor_name" id="dep_contributor" class="form-control" placeholder="e.g. Kazi Abu Sayed" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="dep_user_id" class="form-label">Linked System User</label>
                                <select name="user_id" id="dep_user_id" class="form-control">
                                    <option value="">-- Optional Link --</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" {{ $u->id === auth()->id() ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="dep_amount" class="form-label">Deposit Amount (BDT)</label>
                                <input type="number" step="0.01" name="amount" id="dep_amount" class="form-control" placeholder="e.g. 200000" required>
                            </div>
                            <div class="form-group">
                                <label for="dep_date" class="form-label">Deposit Date</label>
                                <input type="date" name="deposit_date" id="dep_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="dep_payment_method" class="form-label">Payment Method</label>
                                <select name="payment_method" id="dep_payment_method" class="form-control" required>
                                    @foreach ($paymentMethods as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dep_reference_no" class="form-label">Bank Ref / Cheque No</label>
                                <input type="text" name="reference_no" id="dep_reference_no" class="form-control" placeholder="e.g. CHQ-990182">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dep_description" class="form-label">Deposit Purpose / Description</label>
                            <input type="text" name="description" id="dep_description" class="form-control" placeholder="e.g. Initial capital share contribution">
                        </div>

                        <div class="form-group">
                            <label for="dep_document" class="form-label">Attach Deposit Slip (PDF or Image)</label>
                            <input type="file" name="document" id="dep_document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" style="padding-top: 0.35rem;">
                            <span style="font-size: 0.6875rem; color: var(--gray-400); margin-top: 0.2rem; display: block;">Saved to Namecheap FTP storage.</span>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem;">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('depositModal')">Cancel</button>
                            <button type="submit" class="btn btn-emerald">Save Capital Deposit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- JavaScript for Tabs and Modals -->
    <script>
        function switchTab(tabId) {
            // Update Tab Buttons
            document.querySelectorAll('.nav-tab-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if (activeBtn) activeBtn.classList.add('active');

            // Update Tab Panes
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            const activePane = document.getElementById('pane-' + tabId);
            if (activePane) activePane.classList.add('active');

            // Update hidden filter input
            const filterTab = document.getElementById('filterTabInput');
            if (filterTab) filterTab.value = tabId;

            // Sync URL parameter without reload
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabId);
            window.history.replaceState({}, '', url.toString());
        }

        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('open');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('open');
        }

        // Close modal when clicking on backdrop
        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('entry-modal-backdrop')) {
                e.target.classList.remove('open');
            }
        });

        // Close on Escape key
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.entry-modal-backdrop.open').forEach(m => m.classList.remove('open'));
            }
        });
    </script>
</x-layouts.app>
