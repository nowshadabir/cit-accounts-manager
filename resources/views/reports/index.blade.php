<x-layouts.app title="Financial Reports & Export">
    <style>
        /* Header Area */
        .report-header-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .report-title-group h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .report-title-group p {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 0.15rem;
        }

        .header-actions-bar {
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
            gap: 0.45rem;
            transition: all 0.15s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .btn-excel {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
        }

        .btn-excel:hover {
            background-color: #047857;
        }

        .btn-secondary {
            background-color: #ffffff;
            border-color: var(--gray-300);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background-color: var(--gray-50);
            border-color: var(--gray-400);
            color: var(--gray-900);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        /* Streamlined Filter Bar */
        .filter-container {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .primary-filter-row {
            display: grid;
            grid-template-columns: 2fr 1.5fr 3fr auto auto auto;
            align-items: center;
            gap: 0.75rem;
        }

        .filter-select {
            height: 38px;
            padding: 0 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-700);
            background-color: #ffffff;
            outline: none;
            width: 100%;
            cursor: pointer;
        }

        .filter-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-box-wrap {
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 0 0.75rem 0 2.25rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.8125rem;
            outline: none;
            background: #ffffff;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-icon-svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
        }

        /* Advanced Options Collapsible */
        .advanced-filters-panel {
            display: {{ ($startDate || $endDate || $disbursalId || $category) ? 'grid' : 'none' }};
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            margin-top: 0.875rem;
            padding-top: 0.875rem;
            border-top: 1px dashed var(--gray-200);
        }

        .adv-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .toggle-adv-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        .toggle-adv-btn:hover {
            background: #eff6ff;
        }

        /* Executive KPI Bar */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .kpi-card-simple {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.125rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.02);
            transition: all 0.15s ease;
        }

        .kpi-card-simple:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 24, 40, 0.04);
            border-color: var(--gray-300);
        }

        .kpi-meta-left {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            min-width: 0;
        }

        .kpi-sub-title {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .kpi-big-num {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
        }

        .kpi-count-hint {
            font-size: 0.7rem;
            color: var(--gray-400);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .kpi-icon-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Segmented Tab Bar */
        .report-tabs-bar {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 0.5rem;
            overflow-x: auto;
        }

        .tab-btn {
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-600);
            background: transparent;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--gray-900);
            background: var(--gray-100);
        }

        .tab-btn.active {
            color: var(--primary);
            background: #eff6ff;
            font-weight: 700;
        }

        .tab-count {
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 0.1rem 0.45rem;
            border-radius: 9999px;
            background: #e2e8f0;
            color: #475569;
        }

        .tab-btn.active .tab-count {
            background: #bfdbfe;
            color: #1e40af;
        }

        /* Card Container */
        .report-pane-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.03);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .pane-header-bar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .pane-header-title {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .pane-header-desc {
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
            padding: 0.65rem 1rem;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gray-500);
            border-bottom: 1px solid var(--gray-200);
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background-color: #fafbfc;
        }

        .data-table tfoot td {
            background: #f8fafc;
            padding: 0.75rem 1rem;
            font-weight: 800;
            color: var(--gray-900);
            border-top: 1px solid var(--gray-300);
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.55rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .badge-reimburse {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .badge-holding {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .badge-settled {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .doc-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.45rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            text-decoration: none;
        }

        .doc-badge:hover {
            background: #dcfce7;
        }

        /* Tab Panes */
        .report-tab-pane {
            display: none;
        }

        .report-tab-pane.active {
            display: block;
        }

        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--gray-400);
            font-size: 0.875rem;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Tablet & Mobile Grid Breakpoints */
        @media (max-width: 1024px) {
            .kpi-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .primary-filter-row {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .search-box-wrap {
                grid-column: span 2;
            }

            .advanced-filters-panel {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .report-header-wrap {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.875rem;
            }

            .header-actions-bar {
                width: 100%;
                display: flex;
                flex-direction: row;
                gap: 0.5rem;
            }

            .header-actions-bar .btn {
                flex: 1;
                justify-content: center;
                height: 40px;
                font-size: 0.78rem;
            }

            .primary-filter-row {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0.65rem;
            }

            .filter-select {
                width: 100%;
                min-width: 0;
                height: 42px;
                font-size: 16px; /* iOS auto-zoom prevention */
            }

            .search-box-wrap {
                width: 100%;
                min-width: 0;
            }

            .search-input {
                height: 42px;
                font-size: 16px;
            }

            .advanced-filters-panel {
                grid-template-columns: 1fr;
            }

            .kpi-row {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.75rem;
            }

            .kpi-card-simple {
                padding: 0.875rem;
            }

            .kpi-big-num {
                font-size: 1.15rem;
            }

            .report-tabs-bar {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding-bottom: 6px;
            }

            .report-tabs-bar::-webkit-scrollbar {
                display: none;
            }

            .tab-btn {
                padding: 0.45rem 0.75rem;
                font-size: 0.75rem;
                flex-shrink: 0;
            }
        }

        @media (max-width: 480px) {
            .kpi-row {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.625rem;
            }

            .kpi-card-simple {
                padding: 0.75rem 0.65rem;
            }

            .kpi-sub-title {
                font-size: 0.625rem;
            }

            .kpi-big-num {
                font-size: 1.05rem;
            }

            .kpi-count-hint {
                font-size: 0.65rem;
            }

            .kpi-icon-badge {
                width: 34px;
                height: 34px;
                border-radius: 8px;
            }

            .kpi-icon-badge svg {
                width: 16px;
                height: 16px;
            }
        }

        /* Print formatting */
        @media print {
            .app-sidebar,
            .app-topbar,
            .mobile-bottom-nav,
            .filter-container,
            .report-tabs-bar,
            .header-actions-bar,
            .mobile-sidebar-toggle,
            .sidebar-overlay {
                display: none !important;
            }

            .main-content, .app-content {
                padding: 0 !important;
                max-width: 100% !important;
            }

            .report-tab-pane {
                display: block !important;
                margin-bottom: 2rem !important;
                page-break-inside: avoid;
            }

            .report-pane-card {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
            }
        }
    </style>

    <!-- Top Report Header -->
    <div class="report-header-wrap">
        <div class="report-title-group">
            <h1>Financial Reports & Statements</h1>
            <p>
                Period: 
                <strong>
                    @if ($startDate && $endDate)
                        {{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}
                    @elseif ($startDate)
                        From {{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }}
                    @else
                        All Historical Records
                    @endif
                </strong>
                @if ($selectedUser)
                    &bull; Member: <strong>{{ $selectedUser->name }}</strong>
                @endif
            </p>
        </div>

        <div class="header-actions-bar">
            <button type="button" class="btn btn-secondary" onclick="window.print()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>Print / PDF</span>
            </button>

            <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-excel">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span>Export to Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- Clean, Single-Bar Filter Box -->
    <div class="filter-container">
        <form method="GET" action="{{ route('reports.index') }}" id="reportFilterForm">
            <div class="primary-filter-row">
                <!-- Member Dropdown -->
                <select name="user_id" id="filter_user_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Members (Entire Pool)</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ (string)$selectedUserId === (string)$u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Timespan Dropdown -->
                <select name="date_range" id="filter_date_range" class="filter-select" onchange="handleTimespanChange(this.value)">
                    <option value="all" {{ $datePreset === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ $datePreset === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_week" {{ $datePreset === 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ $datePreset === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $datePreset === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="this_quarter" {{ $datePreset === 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="this_year" {{ $datePreset === 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ $datePreset === 'custom' ? 'selected' : '' }}>Custom Dates &hellip;</option>
                </select>

                <!-- Keyword Search -->
                <div class="search-box-wrap">
                    <svg class="search-icon-svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" class="search-input" value="{{ $search }}" placeholder="Search purpose, description, ref...">
                </div>

                <button type="submit" class="btn btn-primary" style="height: 38px;">
                    Filter
                </button>

                @if ($selectedUserId || $datePreset !== 'all' || $search || $disbursalId || $category || $startDate || $endDate)
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary" style="height: 38px;">Reset</a>
                @endif

                <button type="button" class="toggle-adv-btn" id="toggleAdvBtn" onclick="toggleAdvancedFilters()">
                    <span id="advBtnLabel">{{ ($startDate || $endDate || $disbursalId || $category) ? 'Hide Filters' : 'More Options ▾' }}</span>
                </button>
            </div>

            <!-- Advanced / Custom Date Filter Grid -->
            <div class="advanced-filters-panel" id="advancedPanel">
                <div>
                    <label class="adv-label">From Date</label>
                    <input type="date" name="start_date" id="filter_start_date" class="filter-select" style="width: 100%;" value="{{ $startDate }}">
                </div>
                <div>
                    <label class="adv-label">To Date</label>
                    <input type="date" name="end_date" id="filter_end_date" class="filter-select" style="width: 100%;" value="{{ $endDate }}">
                </div>
                <div>
                    <label class="adv-label">Specific Advance</label>
                    <select name="disbursal_id" class="filter-select" style="width: 100%;">
                        <option value="">-- Any Disbursal --</option>
                        @foreach ($allDisbursalsList as $d)
                            <option value="{{ $d->id }}" {{ (string)$disbursalId === (string)$d->id ? 'selected' : '' }}>
                                DIS-{{ str_pad($d->id, 4, '0', STR_PAD_LEFT) }}: ৳{{ number_format($d->amount, 0) }} ({{ Str::limit($d->purpose, 18) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="adv-label">Expense Category</label>
                    <select name="category" class="filter-select" style="width: 100%;">
                        <option value="">-- All Categories --</option>
                        @foreach ($categories as $catKey => $catName)
                            <option value="{{ $catKey }}" {{ $category === $catKey ? 'selected' : '' }}>{{ $catName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Executive KPI Row -->
    <div class="kpi-row">
        <div class="kpi-card-simple">
            <div class="kpi-meta-left">
                <span class="kpi-sub-title">Capital Raised</span>
                <span class="kpi-big-num" style="color: #059669;">৳ {{ number_format($totalDeposits, 2) }}</span>
                <span class="kpi-count-hint">{{ $deposits->count() }} deposits into treasury</span>
            </div>
            <div class="kpi-icon-badge" style="background: #ecfdf5; color: #059669;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
        </div>

        <div class="kpi-card-simple">
            <div class="kpi-meta-left">
                <span class="kpi-sub-title">Advances Disbursed</span>
                <span class="kpi-big-num" style="color: #4f46e5;">৳ {{ number_format($totalDisbursals, 2) }}</span>
                <span class="kpi-count-hint">{{ $disbursals->count() }} advances given</span>
            </div>
            <div class="kpi-icon-badge" style="background: #eef2ff; color: #4f46e5;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="12 5 19 12 12 19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </div>
        </div>

        <div class="kpi-card-simple">
            <div class="kpi-meta-left">
                <span class="kpi-sub-title">Documented Expenses</span>
                <span class="kpi-big-num" style="color: #e11d48;">৳ {{ number_format($totalExpenses, 2) }}</span>
                <span class="kpi-count-hint">{{ $expenses->count() }} verified vouchers</span>
            </div>
            <div class="kpi-icon-badge" style="background: #fff1f2; color: #e11d48;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="16" height="20" x="4" y="2" rx="2"/>
                    <line x1="8" y1="6" x2="16" y2="6"/>
                    <line x1="8" y1="10" x2="16" y2="10"/>
                    <line x1="8" y1="14" x2="12" y2="14"/>
                </svg>
            </div>
        </div>

        <div class="kpi-card-simple">
            <div class="kpi-meta-left">
                <span class="kpi-sub-title">Net Operating Fund</span>
                <span class="kpi-big-num" style="color: {{ $netOperatingBalance >= 0 ? '#0f766e' : '#dc2626' }};">
                    ৳ {{ number_format($netOperatingBalance, 2) }}
                </span>
                <span class="kpi-count-hint">Deposits &minus; Expenses</span>
            </div>
            <div class="kpi-icon-badge" style="background: #f0fdfa; color: #0f766e;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Segmented Tab Navigation for clean, un-cluttered views -->
    <div class="report-tabs-bar">
        <button type="button" class="tab-btn active" onclick="switchReportTab('settlements')">
            <span>Settlement Ledger</span>
            <span class="tab-count">{{ count($userLedgers) }}</span>
        </button>

        <button type="button" class="tab-btn" onclick="switchReportTab('advances')">
            <span>Fund Advances</span>
            <span class="tab-count">{{ $disbursals->count() }}</span>
        </button>

        <button type="button" class="tab-btn" onclick="switchReportTab('expenses')">
            <span>Expense Vouchers</span>
            <span class="tab-count">{{ $expenses->count() }}</span>
        </button>

        <button type="button" class="tab-btn" onclick="switchReportTab('deposits')">
            <span>Capital Deposits</span>
            <span class="tab-count">{{ $deposits->count() }}</span>
        </button>

        <button type="button" class="tab-btn" onclick="switchReportTab('transactions')">
            <span>All Timeline Activity</span>
            <span class="tab-count">{{ $transactions->count() }}</span>
        </button>
    </div>

    <!-- TAB 1: SETTLEMENT LEDGER -->
    <div class="report-tab-pane active" id="tab-pane-settlements">
        <div class="report-pane-card">
            <div class="pane-header-bar">
                <div>
                    <div class="pane-header-title">Member Settlement & Reconciliation Ledger</div>
                    <div class="pane-header-desc">Compares advances allocated vs expenses claimed by each member</div>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Advances Received</th>
                            <th>Total Spent</th>
                            <th>Net Balance</th>
                            <th>Settlement Status</th>
                            <th style="text-align: right;">Transactions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($userLedgers as $ledger)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--gray-900);">{{ $ledger['user']->name }}</div>
                                    <div style="font-size: 0.72rem; color: var(--gray-400);">{{ $ledger['user']->email }}</div>
                                </td>
                                <td style="font-weight: 600; color: #4f46e5;">
                                    ৳ {{ number_format($ledger['allocated'], 2) }}
                                </td>
                                <td style="font-weight: 600; color: #e11d48;">
                                    ৳ {{ number_format($ledger['spent'], 2) }}
                                </td>
                                <td style="font-weight: 700; color: {{ $ledger['balance'] >= 0 ? '#1e293b' : '#dc2626' }};">
                                    ৳ {{ number_format($ledger['balance'], 2) }}
                                </td>
                                <td>
                                    @if ($ledger['status'] === 'reimburse')
                                        <span class="badge badge-reimburse">
                                            Will receive ৳ {{ number_format($ledger['due_reimbursement'], 2) }} from institute
                                        </span>
                                    @elseif ($ledger['status'] === 'holding')
                                        <span class="badge badge-holding">
                                            Holding ৳ {{ number_format($ledger['unspent_fund'], 2) }} unspent fund
                                        </span>
                                    @else
                                        <span class="badge badge-settled">Settled & Balanced</span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-size: 0.75rem; color: var(--gray-500);">
                                    {{ $ledger['disbursal_count'] }} advances / {{ $ledger['expense_count'] }} vouchers
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">No member records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total Summary</td>
                            <td style="color: #4f46e5;">৳ {{ number_format(collect($userLedgers)->sum('allocated'), 2) }}</td>
                            <td style="color: #e11d48;">৳ {{ number_format(collect($userLedgers)->sum('spent'), 2) }}</td>
                            <td>৳ {{ number_format(collect($userLedgers)->sum('balance'), 2) }}</td>
                            <td colspan="2" style="font-size: 0.75rem; color: var(--gray-600);">
                                Reimbursements Due: <strong>৳ {{ number_format($totalReimbursementDue, 2) }}</strong> &bull; Unspent Advance: <strong>৳ {{ number_format($totalHoldingByUsers, 2) }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: FUND ADVANCES -->
    <div class="report-tab-pane" id="tab-pane-advances">
        <div class="report-pane-card">
            <div class="pane-header-bar">
                <div>
                    <div class="pane-header-title">Fund Advances & Transfers</div>
                    <div class="pane-header-desc">Tracks all advance disbursements and transfer directions</div>
                </div>
                <div style="font-weight: 700; color: #4f46e5; font-size: 0.8125rem;">
                    Subtotal: ৳ {{ number_format($totalDisbursals, 2) }}
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Date</th>
                            <th>Transfer Flow (From ➔ To)</th>
                            <th>Purpose</th>
                            <th>Method</th>
                            <th style="text-align: right;">Amount (BDT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($disbursals as $disbursal)
                            <tr>
                                <td style="font-size: 0.72rem; font-family: monospace; font-weight: 700; color: var(--gray-500);">
                                    DIS-{{ str_pad($disbursal->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="white-space: nowrap; font-size: 0.75rem;">
                                    {{ $disbursal->disbursal_date ? $disbursal->disbursal_date->format('d M, Y') : 'N/A' }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">
                                        <span style="color: #64748b;">{{ $disbursal->givenBy->name ?? 'Treasury' }}</span>
                                        <span style="color: #6366f1; margin: 0 0.2rem;">➔</span>
                                        <span style="color: #3730a3; font-weight: 700;">{{ $disbursal->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $disbursal->purpose }}</div>
                                    @if ($disbursal->notes)
                                        <div style="font-size: 0.72rem; color: var(--gray-500);">{{ $disbursal->notes }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.75rem; color: var(--gray-700);">{{ $disbursal->payment_method }}</span>
                                    @if ($disbursal->reference_no)
                                        <span style="font-size: 0.6875rem; color: var(--gray-400);">({{ $disbursal->reference_no }})</span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #4f46e5; white-space: nowrap;">
                                    ৳ {{ number_format($disbursal->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">No fund advances recorded for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Subtotal</td>
                            <td style="text-align: right; color: #4f46e5;">৳ {{ number_format($totalDisbursals, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: EXPENSE VOUCHERS -->
    <div class="report-tab-pane" id="tab-pane-expenses">
        <div class="report-pane-card">
            <div class="pane-header-bar">
                <div>
                    <div class="pane-header-title">Documented Expense Vouchers</div>
                    <div class="pane-header-desc">Verified receipts and documented purchases</div>
                </div>
                <div style="font-weight: 700; color: #e11d48; font-size: 0.8125rem;">
                    Subtotal: ৳ {{ number_format($totalExpenses, 2) }}
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Date</th>
                            <th>Claimed By</th>
                            <th>Category</th>
                            <th>Title & Particulars</th>
                            <th>Receipt Doc</th>
                            <th style="text-align: right;">Amount (BDT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td style="font-size: 0.72rem; font-family: monospace; font-weight: 700; color: var(--gray-500);">
                                    EXP-{{ str_pad($expense->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="white-space: nowrap; font-size: 0.75rem;">
                                    {{ $expense->expense_date ? $expense->expense_date->format('d M, Y') : 'N/A' }}
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--gray-900);">{{ $expense->user->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                                        {{ $expense->category }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $expense->title }}</div>
                                    @if ($expense->description)
                                        <div style="font-size: 0.72rem; color: var(--gray-500);">{{ $expense->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($expense->document_path)
                                        <a href="{{ route('documents.download', ['type' => 'expense', 'id' => $expense->id]) }}" class="doc-badge" title="Download Document">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            <span>Doc</span>
                                        </a>
                                    @else
                                        <span style="font-size: 0.72rem; color: var(--gray-400); font-style: italic;">No Doc</span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #e11d48; white-space: nowrap;">
                                    ৳ {{ number_format($expense->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">No expense vouchers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Subtotal</td>
                            <td style="text-align: right; color: #e11d48;">৳ {{ number_format($totalExpenses, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: CAPITAL DEPOSITS -->
    <div class="report-tab-pane" id="tab-pane-deposits">
        <div class="report-pane-card">
            <div class="pane-header-bar">
                <div>
                    <div class="pane-header-title">Capital Deposits & Inflows</div>
                    <div class="pane-header-desc">Funds contributed to the central pool treasury</div>
                </div>
                <div style="font-weight: 700; color: #059669; font-size: 0.8125rem;">
                    Subtotal: ৳ {{ number_format($totalDeposits, 2) }}
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Date</th>
                            <th>Contributor</th>
                            <th>Payment Method & Ref</th>
                            <th>Description</th>
                            <th>Slip Doc</th>
                            <th style="text-align: right;">Amount (BDT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deposits as $deposit)
                            <tr>
                                <td style="font-size: 0.72rem; font-family: monospace; font-weight: 700; color: var(--gray-500);">
                                    DEP-{{ str_pad($deposit->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="white-space: nowrap; font-size: 0.75rem;">
                                    {{ $deposit->deposit_date ? $deposit->deposit_date->format('d M, Y') : 'N/A' }}
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--gray-900);">{{ $deposit->contributor_name }}</div>
                                    @if ($deposit->user)
                                        <div style="font-size: 0.72rem; color: var(--gray-400);">Linked: {{ $deposit->user->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.75rem; color: var(--gray-700);">{{ $deposit->payment_method }}</span>
                                    @if ($deposit->reference_no)
                                        <span style="font-size: 0.6875rem; color: var(--gray-400);">({{ $deposit->reference_no }})</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="color: var(--gray-700);">{{ $deposit->description ?: 'Capital share deposit' }}</span>
                                </td>
                                <td>
                                    @if ($deposit->document_path)
                                        <a href="{{ route('documents.download', ['type' => 'deposit', 'id' => $deposit->id]) }}" class="doc-badge" title="Download Deposit Slip">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            <span>Slip</span>
                                        </a>
                                    @else
                                        <span style="font-size: 0.72rem; color: var(--gray-400); font-style: italic;">No Doc</span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #059669; white-space: nowrap;">
                                    ৳ {{ number_format($deposit->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">No capital deposits recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Subtotal</td>
                            <td style="text-align: right; color: #059669;">৳ {{ number_format($totalDeposits, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 5: ALL TRANSACTIONS TIMELINE -->
    <div class="report-tab-pane" id="tab-pane-transactions">
        <div class="report-pane-card">
            <div class="pane-header-bar">
                <div>
                    <div class="pane-header-title">Chronological Activity Timeline</div>
                    <div class="pane-header-desc">Combined list of all deposits, advances, and expense vouchers</div>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Party / Member</th>
                            <th>Particulars / Purpose</th>
                            <th>Method / Category</th>
                            <th style="text-align: right;">Amount (BDT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $tx)
                            <tr>
                                <td style="font-size: 0.72rem; font-family: monospace; font-weight: 700; color: var(--gray-500);">
                                    {{ $tx['ref_id'] }}
                                </td>
                                <td style="white-space: nowrap; font-size: 0.75rem;">
                                    {{ $tx['date']->format('d M, Y') }}
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $tx['type'] === 'Deposit' ? '#ecfdf5' : ($tx['type'] === 'Expense' ? '#fff1f2' : '#eef2ff') }}; color: {{ $tx['type'] === 'Deposit' ? '#047857' : ($tx['type'] === 'Expense' ? '#be123c' : '#4338ca') }}; border: 1px solid {{ $tx['type'] === 'Deposit' ? '#a7f3d0' : ($tx['type'] === 'Expense' ? '#fecdd3' : '#c7d2fe') }};">
                                        {{ $tx['type'] }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--gray-900);">{{ $tx['member'] }}</div>
                                </td>
                                <td>
                                    <div style="color: var(--gray-800);">{{ $tx['title'] }}</div>
                                </td>
                                <td>
                                    <span style="font-size: 0.75rem; color: var(--gray-600);">{{ $tx['category_method'] }}</span>
                                </td>
                                <td style="text-align: right; font-weight: 700; color: {{ $tx['type'] === 'Deposit' ? '#059669' : ($tx['type'] === 'Expense' ? '#e11d48' : '#4f46e5') }}; white-space: nowrap;">
                                    ৳ {{ number_format($tx['amount'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">No financial activity recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function switchReportTab(tabKey) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.report-tab-pane').forEach(pane => pane.classList.remove('active'));

            const targetBtn = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.getAttribute('onclick')?.includes(tabKey));
            if (targetBtn) targetBtn.classList.add('active');

            const targetPane = document.getElementById('tab-pane-' + tabKey);
            if (targetPane) targetPane.classList.add('active');
        }

        function handleTimespanChange(val) {
            const form = document.getElementById('reportFilterForm');
            const advPanel = document.getElementById('advancedPanel');
            if (val === 'custom') {
                if (advPanel) advPanel.style.display = 'grid';
                document.getElementById('filter_start_date')?.focus();
            } else {
                form?.submit();
            }
        }

        function toggleAdvancedFilters() {
            const panel = document.getElementById('advancedPanel');
            const label = document.getElementById('advBtnLabel');
            if (panel) {
                const isHidden = panel.style.display === 'none' || panel.style.display === '';
                panel.style.display = isHidden ? 'grid' : 'none';
                if (label) label.textContent = isHidden ? 'Hide Filters ▴' : 'More Options ▾';
            }
        }
    </script>
</x-layouts.app>
