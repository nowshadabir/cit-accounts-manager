<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login &mdash; Accounts Management CIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --danger-border: #fecaca;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            color: var(--gray-800);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
                align-items: flex-start;
                padding-top: 2rem;
            }
            .login-card {
                padding: 1.75rem 1.25rem;
                border-radius: 16px;
            }
            .form-control {
                font-size: 16px !important;
                height: 48px !important;
            }
            .btn-submit {
                height: 50px !important;
                font-size: 1rem !important;
            }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            margin-bottom: 1rem;
            box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.35);
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .brand-subtitle {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .alert-error {
            background-color: var(--danger-light);
            border: 1px solid var(--danger-border);
            color: #b91c1c;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 46px;
            padding: 0.625rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            background-color: var(--gray-50);
            color: var(--gray-900);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background-color: #fffafb;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s;
        }

        .toggle-password:hover {
            color: var(--gray-700);
        }

        .field-error {
            font-size: 0.8125rem;
            color: var(--danger);
            margin-top: 0.375rem;
            display: block;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.25rem 0 1.5rem;
            font-size: 0.875rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--gray-600);
            font-weight: 500;
            user-select: none;
        }

        .remember-checkbox {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s;
        }

        .forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: var(--primary);
            color: #ffffff;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .footer-note {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.8125rem;
            color: var(--gray-400);
        }
    </style>
</head>
<body>
    <main class="login-card">
        <header class="brand-header">
            <div class="brand-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 7h10"/>
                    <path d="M7 12h10"/>
                    <path d="M7 17h10"/>
                </svg>
            </div>
            <h1 class="brand-title">Welcome Back</h1>
            <p class="brand-subtitle">CIT Accounts Management System</p>
        </header>

        @if (session('status'))
            <div class="alert-error" style="background-color: #ecfdf5; border-color: #a7f3d0; color: #065f46;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email') }}" 
                        placeholder="e.g. ksayed118@gmail.com"
                        required 
                        autofocus 
                        autocomplete="email"
                    >
                </div>
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="••••••••"
                        required 
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                        <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-options">
                <label class="remember-label" for="remember">
                    <input type="checkbox" name="remember" id="remember" class="remember-checkbox" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>

                <a href="#forgot-password" class="forgot-link" title="Password reset will be available soon">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit">
                <span>Sign In</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
        </form>

        <footer class="footer-note">
            &copy; {{ date('Y') }} CIT Accounts Management. All rights reserved.
        </footer>
    </main>

    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                
                if (isPassword) {
                    eyeIcon.innerHTML = `
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                        <line x1="2" y1="2" x2="22" y2="22"/>
                    `;
                } else {
                    eyeIcon.innerHTML = `
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    `;
                }
            });
        }
    </script>
</body>
</html>
