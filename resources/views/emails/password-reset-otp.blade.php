<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 32px 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand {
            font-size: 18px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.02em;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 12px 0 6px;
        }
        .desc {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }
        .otp-box {
            background: #f1f5f9;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #1d4ed8;
            font-family: 'Courier New', Courier, monospace;
        }
        .warning {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.4;
        }
        .footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">CIT Accounts Management</div>
            <h2 class="title">Password Reset Verification</h2>
            <p class="desc">Hello {{ $user->name }},<br>You requested to change your password. Use the single-use verification code below to complete your password update:</p>
        </div>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p class="warning">
            This verification code is valid for <strong>10 minutes</strong>.<br>
            If you did not request this password reset, please ignore this email or contact the super administrator immediately.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} CIT Accounts Management. All rights reserved.
        </div>
    </div>
</body>
</html>
