<x-layouts.app title="Settings">
    <style>
        .settings-header {
            margin-bottom: 2rem;
        }

        .settings-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .settings-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .settings-card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
            overflow: hidden;
        }

        .card-header-bar {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .header-title-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-mail {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .icon-server {
            background-color: #f0fdf4;
            color: #16a34a;
        }

        .header-heading {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .header-subtext {
            font-size: 0.8125rem;
            color: var(--gray-500);
        }

        .card-body-content {
            padding: 1.75rem 1.5rem;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.375rem;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.35rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
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

        .toggle-btn {
            position: absolute;
            right: 0.625rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
        }

        .toggle-btn:hover {
            color: var(--gray-700);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin: 1.25rem 0;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            cursor: pointer;
            user-select: none;
        }

        .checkbox-input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .alert-box {
            padding: 0.875rem 1rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            line-height: 1.4;
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

        .alert-info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        .card-actions-bar {
            padding: 1.25rem 1.5rem;
            background-color: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
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

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .test-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            max-width: 420px;
        }

        .test-result-box {
            margin-top: 1.25rem;
            display: none;
        }

        @media (max-width: 768px) {
            .settings-header {
                margin-bottom: 1.25rem;
            }
            .settings-title {
                font-size: 1.25rem;
            }
            .form-row-2, .form-row-3 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .form-control {
                font-size: 16px !important; /* Prevents iOS auto zoom */
                height: 44px;
            }
            .card-actions-bar {
                flex-direction: column;
                align-items: stretch;
                padding: 1rem;
                gap: 0.75rem;
            }
            .test-box {
                max-width: 100%;
                flex-direction: column;
                align-items: stretch;
            }
            .test-box .btn, .card-actions-bar .btn {
                width: 100%;
                height: 44px;
                font-size: 0.9375rem;
            }
            .checkbox-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
        }
    </style>

    <div class="settings-header">
        <h1 class="settings-title">System Settings</h1>
        <p class="settings-desc">Configure email delivery and remote file storage for images and PDF documents.</p>
    </div>

    <div class="settings-grid">
        <!-- 1. GMAIL SMTP CONFIGURATION -->
        <section class="settings-card" id="gmail-smtp">
            <div class="card-header-bar">
                <div class="header-title-group">
                    <div class="header-icon icon-mail">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="header-heading">Gmail SMTP Configuration</h2>
                        <span class="header-subtext">Send system notifications, accounts reports, and verification emails.</span>
                    </div>
                </div>
            </div>

            <div class="card-body-content">
                @if (session('mail_status'))
                    <div class="alert-box alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('mail_status') }}</span>
                    </div>
                @endif

                @if (session('mail_error'))
                    <div class="alert-box alert-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ session('mail_error') }}</span>
                    </div>
                @endif

                <div class="alert-box alert-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <div>
                        <strong>Gmail Setup Guide:</strong> For Gmail, turn on 2-Step Verification in your Google Account and generate an <strong>App Password</strong> (16 letters). Use that password below instead of your normal account password.
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.mail.update') }}" id="mailSettingsForm">
                    @csrf

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="mail_host" class="form-label">SMTP Host</label>
                            <input type="text" name="mail_host" id="mail_host" class="form-control" value="{{ old('mail_host', $mailSettings['mail_host']) }}" placeholder="smtp.gmail.com" required>
                        </div>
                        <div class="form-group">
                            <label for="mail_port" class="form-label">Port</label>
                            <input type="number" name="mail_port" id="mail_port" class="form-control" value="{{ old('mail_port', $mailSettings['mail_port']) }}" placeholder="587" required>
                        </div>
                        <div class="form-group">
                            <label for="mail_encryption" class="form-label">Encryption</label>
                            <select name="mail_encryption" id="mail_encryption" class="form-control" required>
                                <option value="tls" {{ old('mail_encryption', $mailSettings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                                <option value="ssl" {{ old('mail_encryption', $mailSettings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                                <option value="none" {{ old('mail_encryption', $mailSettings['mail_encryption']) === 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="mail_username" class="form-label">Gmail Address / Username</label>
                            <input type="email" name="mail_username" id="mail_username" class="form-control" value="{{ old('mail_username', $mailSettings['mail_username']) }}" placeholder="youremail@gmail.com" required>
                            <span class="form-hint">Your complete Gmail or Google Workspace address.</span>
                        </div>
                        <div class="form-group">
                            <label for="mail_password" class="form-label">Google App Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="mail_password" id="mail_password" class="form-control" value="" placeholder="{{ $mailSettings['mail_password'] ? '•••••••••••••••• (Saved - leave blank to keep)' : '16-character app password' }}">
                                <button type="button" class="toggle-btn" onclick="toggleVisibility('mail_password')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="form-hint">Leave untouched to keep the saved App Password.</span>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="mail_from_address" class="form-label">From Email Address</label>
                            <input type="email" name="mail_from_address" id="mail_from_address" class="form-control" value="{{ old('mail_from_address', $mailSettings['mail_from_address']) }}" placeholder="no-reply@cit-accounts.com" required>
                        </div>
                        <div class="form-group">
                            <label for="mail_from_name" class="form-label">From Sender Name</label>
                            <input type="text" name="mail_from_name" id="mail_from_name" class="form-control" value="{{ old('mail_from_name', $mailSettings['mail_from_name']) }}" placeholder="CIT Accounts Management" required>
                        </div>
                    </div>
                </form>

                <div id="mailTestResult" class="test-result-box"></div>
            </div>

            <div class="card-actions-bar">
                <div class="test-box">
                    <input type="email" id="testEmailRecipient" class="form-control" placeholder="Recipient email for test" style="height: 38px;">
                    <button type="button" class="btn btn-secondary" id="btnTestMail">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        <span>Send Test Email</span>
                    </button>
                </div>

                <button type="submit" form="mailSettingsForm" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <span>Save SMTP Settings</span>
                </button>
            </div>
        </section>

        <!-- 2. NAMECHEAP SHARED HOSTING FTP STORAGE -->
        <section class="settings-card" id="namecheap-ftp">
            <div class="card-header-bar">
                <div class="header-title-group">
                    <div class="header-icon icon-server">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="8" x="2" y="2" rx="2" ry="2"/>
                            <rect width="20" height="8" x="2" y="14" rx="2" ry="2"/>
                            <line x1="6" y1="6" x2="6.01" y2="6"/>
                            <line x1="6" y1="18" x2="6.01" y2="18"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="header-heading">Namecheap Shared Hosting FTP Storage</h2>
                        <span class="header-subtext">Remote FTP storage for vouchers, image receipts, and exported PDF documents.</span>
                    </div>
                </div>
            </div>

            <div class="card-body-content">
                @if (session('ftp_status'))
                    <div class="alert-box alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('ftp_status') }}</span>
                    </div>
                @endif

                @if (session('ftp_error'))
                    <div class="alert-box alert-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ session('ftp_error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.ftp.update') }}" id="ftpSettingsForm">
                    @csrf

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="ftp_host" class="form-label">FTP Host / Server IP</label>
                            <input type="text" name="ftp_host" id="ftp_host" class="form-control" value="{{ old('ftp_host', $ftpSettings['ftp_host']) }}" placeholder="ftp.yourdomain.com or server IP" required>
                            <span class="form-hint">Namecheap FTP hostname found in your cPanel.</span>
                        </div>
                        <div class="form-group">
                            <label for="ftp_port" class="form-label">FTP Port</label>
                            <input type="number" name="ftp_port" id="ftp_port" class="form-control" value="{{ old('ftp_port', $ftpSettings['ftp_port']) }}" placeholder="21" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="ftp_username" class="form-label">FTP Username / cPanel User</label>
                            <input type="text" name="ftp_username" id="ftp_username" class="form-control" value="{{ old('ftp_username', $ftpSettings['ftp_username']) }}" placeholder="cit@ssc94.com" required>
                            <span class="form-hint">For Namecheap sub-accounts, use full <code>user@domain.com</code> format.</span>
                        </div>
                        <div class="form-group">
                            <label for="ftp_password" class="form-label">FTP Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="ftp_password" id="ftp_password" class="form-control" value="" placeholder="{{ $ftpSettings['ftp_password'] ? '•••••••••••••••• (Saved - leave blank to keep)' : 'FTP user password' }}">
                                <button type="button" class="toggle-btn" onclick="toggleVisibility('ftp_password')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="form-hint">Leave untouched to keep the saved password.</span>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="ftp_root" class="form-label">Storage Root Path</label>
                            <input type="text" name="ftp_root" id="ftp_root" class="form-control" value="{{ old('ftp_root', $ftpSettings['ftp_root']) }}" placeholder="/">
                            <span class="form-hint">Use <code>/</code> for chrooted FTP sub-accounts.</span>
                        </div>
                        <div class="form-group">
                            <label for="ftp_base_url" class="form-label">Public Base URL (Optional)</label>
                            <input type="url" name="ftp_base_url" id="ftp_base_url" class="form-control" value="{{ old('ftp_base_url', $ftpSettings['ftp_base_url']) }}" placeholder="https://ssc94.com/cit">
                            <span class="form-hint">Direct URL prefix used to serve images & PDF links.</span>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label" for="ftp_passive">
                            <input type="checkbox" name="ftp_passive" id="ftp_passive" class="checkbox-input" value="1" {{ old('ftp_passive', $ftpSettings['ftp_passive']) ? 'checked' : '' }}>
                            <span>Enable Passive Mode (Recommended for Namecheap cPanel)</span>
                        </label>

                        <label class="checkbox-label" for="ftp_ssl">
                            <input type="checkbox" name="ftp_ssl" id="ftp_ssl" class="checkbox-input" value="1" {{ old('ftp_ssl', $ftpSettings['ftp_ssl']) ? 'checked' : '' }}>
                            <span>Use FTPS (FTP with SSL/TLS)</span>
                        </label>
                    </div>
                </form>

                <div id="ftpTestResult" class="test-result-box"></div>
            </div>

            <div class="card-actions-bar">
                <button type="button" class="btn btn-secondary" id="btnTestFtp">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                    </svg>
                    <span>Test FTP Connection</span>
                </button>

                <button type="submit" form="ftpSettingsForm" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <span>Save FTP Settings</span>
                </button>
            </div>
        </section>
    </div>

    <script>
        function toggleVisibility(id) {
            const input = document.getElementById(id);
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        }

        // Live Test Mail
        const btnTestMail = document.getElementById('btnTestMail');
        const testEmailRecipient = document.getElementById('testEmailRecipient');
        const mailTestResult = document.getElementById('mailTestResult');

        if (btnTestMail) {
            btnTestMail.addEventListener('click', async () => {
                const recipient = testEmailRecipient.value.trim();
                if (!recipient) {
                    alert('Please enter a recipient email address to test.');
                    testEmailRecipient.focus();
                    return;
                }

                btnTestMail.disabled = true;
                btnTestMail.innerHTML = '<span>Sending...</span>';
                mailTestResult.style.display = 'none';

                const payload = {
                    test_email: recipient,
                    mail_host: document.getElementById('mail_host').value.trim(),
                    mail_port: document.getElementById('mail_port').value.trim(),
                    mail_encryption: document.getElementById('mail_encryption').value,
                    mail_username: document.getElementById('mail_username').value.trim(),
                    mail_password: document.getElementById('mail_password').value,
                    mail_from_address: document.getElementById('mail_from_address').value.trim(),
                    mail_from_name: document.getElementById('mail_from_name').value.trim(),
                };

                try {
                    const res = await fetch("{{ route('settings.mail.test') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });
                    
                    const data = await res.json();
                    
                    mailTestResult.style.display = 'flex';
                    if (res.ok && data.success) {
                        mailTestResult.className = 'alert-box alert-success test-result-box';
                        mailTestResult.innerHTML = `<span>✔ ${data.message}</span>`;
                    } else {
                        mailTestResult.className = 'alert-box alert-danger test-result-box';
                        mailTestResult.innerHTML = `<span>✖ ${data.message || 'SMTP Test failed'}</span>`;
                    }
                } catch (err) {
                    mailTestResult.style.display = 'flex';
                    mailTestResult.className = 'alert-box alert-danger test-result-box';
                    mailTestResult.innerHTML = `<span>✖ Request error: ${err.message}</span>`;
                } finally {
                    btnTestMail.disabled = false;
                    btnTestMail.innerHTML = `
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        <span>Send Test Email</span>
                    `;
                }
            });
        }

        // Live Test FTP
        const btnTestFtp = document.getElementById('btnTestFtp');
        const ftpTestResult = document.getElementById('ftpTestResult');

        if (btnTestFtp) {
            btnTestFtp.addEventListener('click', async () => {
                btnTestFtp.disabled = true;
                btnTestFtp.innerHTML = '<span>Connecting & testing...</span>';
                ftpTestResult.style.display = 'none';

                const payload = {
                    ftp_host: document.getElementById('ftp_host').value.trim(),
                    ftp_port: document.getElementById('ftp_port').value.trim(),
                    ftp_username: document.getElementById('ftp_username').value.trim(),
                    ftp_password: document.getElementById('ftp_password').value,
                    ftp_root: document.getElementById('ftp_root').value.trim(),
                    ftp_passive: document.getElementById('ftp_passive').checked ? 1 : 0,
                    ftp_ssl: document.getElementById('ftp_ssl').checked ? 1 : 0,
                };

                try {
                    const res = await fetch("{{ route('settings.ftp.test') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    ftpTestResult.style.display = 'flex';
                    if (res.ok && data.success) {
                        ftpTestResult.className = 'alert-box alert-success test-result-box';
                        ftpTestResult.innerHTML = `<span>✔ ${data.message}</span>`;
                    } else {
                        ftpTestResult.className = 'alert-box alert-danger test-result-box';
                        ftpTestResult.innerHTML = `<span>✖ ${data.message || 'FTP connection test failed'}</span>`;
                    }
                } catch (err) {
                    ftpTestResult.style.display = 'flex';
                    ftpTestResult.className = 'alert-box alert-danger test-result-box';
                    ftpTestResult.innerHTML = `<span>✖ Request error: ${err.message}</span>`;
                } finally {
                    btnTestFtp.disabled = false;
                    btnTestFtp.innerHTML = `
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                        </svg>
                        <span>Test FTP Connection</span>
                    `;
                }
            });
        }
    </script>
</x-layouts.app>
