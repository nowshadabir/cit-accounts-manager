<x-layouts.app title="My Profile">
    <style>
        .profile-page-header {
            margin-bottom: 2rem;
        }

        .profile-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .profile-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        @media (max-width: 860px) {
            .profile-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            .profile-page-header {
                margin-bottom: 1.25rem;
            }
            .profile-title {
                font-size: 1.25rem;
            }
            .form-control {
                font-size: 16px !important; /* Prevents iOS auto zoom */
                height: 44px;
            }
            .otp-input-code {
                font-size: 1.35rem !important;
                height: 48px;
            }
            .btn {
                height: 44px;
                font-size: 0.9375rem;
            }
            .card-body-box {
                padding: 1.25rem;
            }
        }

        .card {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.04);
            overflow: hidden;
        }

        .card-header-box {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: #ffffff;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .card-subtitle {
            font-size: 0.78rem;
            color: var(--gray-500);
            margin-top: 0.15rem;
        }

        .card-body-box {
            padding: 1.5rem;
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
            margin-bottom: 0.35rem;
        }

        .form-control {
            width: 100%;
            height: 40px;
            padding: 0 0.875rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--gray-900);
            background: #ffffff;
            outline: none;
            font-family: inherit;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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

        .btn {
            height: 40px;
            padding: 0 1.25rem;
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

        .btn-secondary {
            background-color: #ffffff;
            border-color: var(--gray-300);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background-color: var(--gray-50);
            color: var(--gray-900);
        }

        .alert-box {
            padding: 0.75rem 1rem;
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

        /* Avatar Header */
        .avatar-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .avatar-circle {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .user-meta-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .user-meta-email {
            font-size: 0.8125rem;
            color: var(--gray-500);
        }

        /* OTP Notice Box */
        .otp-notice-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem 1.125rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .otp-notice-text {
            font-size: 0.8125rem;
            color: var(--gray-600);
            line-height: 1.45;
        }

        .otp-input-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 4px;
            text-align: center;
        }
    </style>

    <div class="profile-page-header">
        <h1 class="profile-title">Account Settings & Profile</h1>
        <p class="profile-desc">Manage your personal details and securely reset your login password.</p>
    </div>

    <div class="profile-grid">
        <!-- 1. Profile Information Card -->
        <div class="card">
            <div class="card-header-box">
                <div>
                    <h2 class="card-title">Profile Information</h2>
                    <p class="card-subtitle">Update your personal account name and email address.</p>
                </div>
            </div>

            <div class="card-body-box">
                @if (session('profile_status'))
                    <div class="alert-box alert-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>{{ session('profile_status') }}</span>
                    </div>
                @endif

                <div class="avatar-header">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-meta-name">{{ $user->name }}</div>
                        <div class="user-meta-email">{{ $user->email }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Member Since</label>
                        <input type="text" class="form-control" value="{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}" disabled style="background: var(--gray-50); color: var(--gray-500);">
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                            </svg>
                            <span>Save Profile</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Password Reset via Email OTP Card -->
        <div class="card">
            <div class="card-header-box">
                <div>
                    <h2 class="card-title">Reset Password</h2>
                    <p class="card-subtitle">Verify with a one-time code (OTP) sent to your email.</p>
                </div>
            </div>

            <div class="card-body-box">
                @if (session('password_status'))
                    <div class="alert-box alert-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>{{ session('password_status') }}</span>
                    </div>
                @endif

                @if (session('password_error'))
                    <div class="alert-box alert-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ session('password_error') }}</span>
                    </div>
                @endif

                <div id="dynamicOtpAlert" style="display: none;" class="alert-box"></div>

                @if (session('otp_status'))
                    <div class="alert-box alert-success" id="otpSuccessAlert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('otp_status') }}</span>
                    </div>
                @endif

                @if (session('otp_error'))
                    <div class="alert-box alert-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ session('otp_error') }}</span>
                    </div>
                @endif

                <div class="otp-notice-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 0.1rem;">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <div class="otp-notice-text">
                        <strong>No current password required.</strong> We will send a single-use 6-digit verification code to <strong>{{ $user->email }}</strong> to verify your identity.
                    </div>
                </div>

                <!-- Step 1: Send OTP trigger -->
                <div style="margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                    <div>
                        <div style="font-size: 0.8125rem; font-weight: 700; color: var(--gray-800);">Step 1: Request Code</div>
                        <div style="font-size: 0.72rem; color: var(--gray-500);">A 6-digit code will be sent to your email</div>
                    </div>

                    <form method="POST" action="{{ route('profile.password.send-otp') }}" id="sendOtpForm">
                        @csrf
                        <button type="submit" class="btn btn-secondary" id="sendOtpBtn">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="sendOtpIcon">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span id="sendOtpBtnText">{{ $otpSent ? 'Resend Verification Code' : 'Send Code to Email' }}</span>
                        </button>
                    </form>
                </div>

                <!-- Step 2: Enter OTP + New Password Form -->
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf

                    <div class="form-group">
                        <label for="otp" class="form-label">
                            Step 2: Enter 6-Digit Email OTP
                            <span style="font-weight: 400; color: var(--gray-500); font-size: 0.72rem;">(valid for 10 minutes)</span>
                        </label>
                        <input type="text" name="otp" id="otp" maxlength="6" class="form-control otp-input-code @error('otp') is-invalid @enderror" placeholder="• • • • • •" value="{{ old('otp') }}" required autocomplete="one-time-code">
                        @error('otp')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 characters" required>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-enter new password" required>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const sendOtpForm = document.getElementById('sendOtpForm');
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const sendOtpBtnText = document.getElementById('sendOtpBtnText');
        const sendOtpIcon = document.getElementById('sendOtpIcon');
        const dynamicOtpAlert = document.getElementById('dynamicOtpAlert');
        const otpInput = document.getElementById('otp');

        let countdownInterval = null;

        function startCountdown(seconds) {
            clearInterval(countdownInterval);
            let remaining = seconds;
            sendOtpBtn.disabled = true;
            sendOtpBtnText.textContent = `Resend in ${remaining}s`;

            countdownInterval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(countdownInterval);
                    sendOtpBtn.disabled = false;
                    sendOtpBtnText.textContent = 'Resend Verification Code';
                } else {
                    sendOtpBtnText.textContent = `Resend in ${remaining}s`;
                }
            }, 1000);
        }

        if (sendOtpForm) {
            sendOtpForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                sendOtpBtn.disabled = true;
                const originalText = sendOtpBtnText.textContent;
                sendOtpBtnText.textContent = 'Sending code...';
                dynamicOtpAlert.style.display = 'none';

                try {
                    const response = await fetch("{{ route('profile.password.send-otp') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    dynamicOtpAlert.style.display = 'flex';
                    if (response.ok && data.success) {
                        dynamicOtpAlert.className = 'alert-box alert-success';
                        dynamicOtpAlert.innerHTML = `
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <span>${data.message}</span>
                        `;
                        if (otpInput) {
                            otpInput.focus();
                        }
                        startCountdown(30);
                    } else {
                        dynamicOtpAlert.className = 'alert-box alert-danger';
                        dynamicOtpAlert.innerHTML = `
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span>${data.message || 'Failed to send OTP code. Please try again.'}</span>
                        `;
                        sendOtpBtn.disabled = false;
                        sendOtpBtnText.textContent = originalText;
                    }
                } catch (err) {
                    dynamicOtpAlert.style.display = 'flex';
                    dynamicOtpAlert.className = 'alert-box alert-danger';
                    dynamicOtpAlert.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>Connection error. Please try again in a few seconds.</span>
                    `;
                    sendOtpBtn.disabled = false;
                    sendOtpBtnText.textContent = originalText;
                }
            });
        }
    </script>
</x-layouts.app>
