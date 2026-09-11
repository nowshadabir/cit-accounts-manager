<x-layouts.app title="Users Management">
    <style>
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
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
            margin-top: 0.25rem;
        }

        .btn {
            height: 40px;
            padding: 0 1.125rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            border: 1px solid var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #ffffff;
            border: 1px solid var(--gray-300);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background-color: var(--gray-50);
            border-color: var(--gray-400);
            color: var(--gray-900);
        }

        .btn-danger-ghost {
            background: none;
            border: 1px solid transparent;
            color: #ef4444;
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .btn-danger-ghost:hover {
            background-color: #fee2e2;
            border-color: #fecaca;
        }

        .alert-box {
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
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

        /* Create User Panel (Collapsible) */
        .create-panel {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            display: {{ $errors->any() ? 'block' : 'none' }};
        }

        .panel-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--gray-50);
        }

        .panel-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .panel-body {
            padding: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.375rem;
        }

        .form-control {
            width: 100%;
            height: 40px;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-family: inherit;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--gray-900);
            transition: all 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .field-error {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 0.25rem;
            display: block;
        }

        /* Table Card */
        .table-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
            overflow: hidden;
        }

        .table-toolbar {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            max-width: 380px;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-family: inherit;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            background: #ffffff;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
        }

        .data-table th {
            background-color: var(--gray-50);
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            border-bottom: 1px solid var(--gray-200);
        }

        .data-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-700);
            vertical-align: middle;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover td {
            background-color: #fafbfc;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .avatar-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 700;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-title {
            font-weight: 600;
            color: var(--gray-900);
            text-transform: capitalize;
        }

        .user-email-text {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* Role Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-super-admin {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-edit {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .badge-view {
            background-color: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .badge-user {
            background-color: #f2f4f7;
            color: #344054;
            border: 1px solid #eaecf0;
        }

        .badge-self {
            font-size: 0.6875rem;
            background: #f1f5f9;
            color: #475569;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .pagination-container {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            background: #ffffff;
        }

        /* Modal */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-backdrop.active {
            display: flex;
        }

        .modal-dialog {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            border: 1px solid var(--gray-200);
            animation: modalPop 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Mobile Responsive & Bottom Sheet Modals */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                margin-bottom: 1.25rem;
            }

            .header-title {
                font-size: 1.25rem;
            }

            .btn {
                height: 44px;
                font-size: 0.9375rem;
            }

            .form-control, .search-input {
                font-size: 16px !important; /* Prevents iOS Safari auto-zoom */
                height: 44px;
            }

            .table-toolbar {
                flex-direction: column;
                align-items: stretch;
                padding: 1rem;
            }

            .search-form {
                max-width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modal-backdrop {
                padding: 0;
                align-items: flex-end;
            }

            .modal-dialog {
                max-width: 100%;
                border-radius: 20px 20px 0 0;
                max-height: 90vh;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                animation: slideUpSheet 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            }

            @keyframes slideUpSheet {
                from {
                    transform: translateY(100%);
                }
                to {
                    transform: translateY(0);
                }
            }

            .modal-header {
                position: sticky;
                top: 0;
                z-index: 10;
                border-radius: 20px 20px 0 0;
            }

            .data-table th, .data-table td {
                padding: 0.75rem 1rem;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="header-title">Users Management</h1>
            <p class="header-desc">Manage system users, assign operational roles, and grant access privileges.</p>
        </div>

        <button type="button" class="btn btn-primary" id="toggleCreateBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <span>Create New User</span>
        </button>
    </div>

    @if (session('status'))
        <div class="alert-box alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-box alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Create User Panel -->
    <div class="create-panel" id="createPanel">
        <div class="panel-header">
            <h2 class="panel-title">Add New User</h2>
            <button type="button" class="btn-danger-ghost" id="closePanelBtn" style="font-size: 0.8125rem;">Cancel</button>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="panel-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g. user@company.com" required>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 characters" required>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">User Role</label>
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $roleKey => $roleName)
                                <option value="{{ $roleKey }}" {{ old('role') === $roleKey ? 'selected' : '' }}>
                                    {{ $roleName }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" id="cancelCreateBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                        </svg>
                        <span>Save User</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="table-card">
        <div class="table-toolbar">
            <form method="GET" action="{{ route('users.index') }}" class="search-form">
                <input type="text" name="search" class="search-input" value="{{ $search }}" placeholder="Search by name, email, or role...">
                <button type="submit" class="btn btn-secondary" style="height: 38px;">Search</button>
                @if ($search)
                    <a href="{{ route('users.index') }}" class="btn btn-secondary" style="height: 38px;">Reset</a>
                @endif
            </form>

            <span style="font-size: 0.8125rem; color: var(--gray-500); font-weight: 500;">
                Total: <strong>{{ $users->total() }}</strong> {{ Str::plural('user', $users->total()) }}
            </span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-box">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-title">
                                            {{ $user->name }}
                                            @if ($user->id === Auth::id())
                                                <span class="badge-self">You</span>
                                            @endif
                                        </div>
                                        <div class="user-email-text">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($user->role) {
                                        'super admin' => 'badge-super-admin',
                                        'edit' => 'badge-edit',
                                        'view' => 'badge-view',
                                        default => 'badge-user',
                                    };
                                    $roleLabel = match($user->role) {
                                        'super admin' => 'Super Admin',
                                        'edit' => 'Edit Access',
                                        'view' => 'View Only',
                                        default => ucfirst($user->role),
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #059669; font-size: 0.8125rem; font-weight: 600;">
                                    <span style="width: 7px; height: 7px; background-color: #10b981; border-radius: 9999px;"></span>
                                    Active
                                </span>
                            </td>
                            <td style="color: var(--gray-500); font-size: 0.8125rem;">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                                    <button type="button" class="btn btn-secondary" style="height: 30px; padding: 0 0.65rem; font-size: 0.75rem;" onclick='openEditUserModal(@json($user))'>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    @if ($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete user \'{{ $user->name }}\'? This action cannot be undone.');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-ghost" style="height: 30px; display: inline-flex; align-items: center;">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.2rem;">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem 1.5rem; color: var(--gray-500);">
                                No users found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="pagination-container">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Edit User Modal -->
    <div class="modal-backdrop" id="editUserModal" onclick="closeEditUserModal(event)">
        <div class="modal-dialog" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">Edit User Access & Details</h3>
                <button type="button" class="btn-danger-ghost" onclick="closeEditUserModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body-content">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="edit_email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="edit_role" class="form-label">Role / Access Level</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            @foreach ($roles as $roleKey => $roleName)
                                <option value="{{ $roleKey }}">{{ $roleName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="edit_password" class="form-label">New Password <span style="font-weight: 400; color: var(--gray-500);">(leave blank to keep unchanged)</span></label>
                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="Minimum 6 characters">
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                        <button type="button" class="btn btn-secondary" onclick="closeEditUserModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                            </svg>
                            <span>Update User</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleCreateBtn');
        const cancelBtn = document.getElementById('cancelCreateBtn');
        const closeBtn = document.getElementById('closePanelBtn');
        const createPanel = document.getElementById('createPanel');
        const editUserModal = document.getElementById('editUserModal');
        const editUserForm = document.getElementById('editUserForm');

        function togglePanel() {
            if (createPanel) {
                const isOpen = createPanel.style.display === 'block';
                createPanel.style.display = isOpen ? 'none' : 'block';
                if (!isOpen) {
                    document.getElementById('name')?.focus();
                }
            }
        }

        toggleBtn?.addEventListener('click', togglePanel);
        cancelBtn?.addEventListener('click', togglePanel);
        closeBtn?.addEventListener('click', togglePanel);

        function openEditUserModal(user) {
            if (!editUserModal || !editUserForm) return;

            editUserForm.action = `/users/${user.id}`;
            document.getElementById('edit_name').value = user.name || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_role').value = user.role || 'view';
            document.getElementById('edit_password').value = '';

            editUserModal.classList.add('active');
        }

        function closeEditUserModal(e) {
            if (editUserModal) {
                editUserModal.classList.remove('active');
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && editUserModal && editUserModal.classList.contains('active')) {
                closeEditUserModal();
            }
        });
    </script>
</x-layouts.app>
