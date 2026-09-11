<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use App\Services\MailConfigService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Display the user's profile view.
     */
    public function show(): View
    {
        $user = Auth::user();
        $otpSent = session('password_otp_sent', false);

        return view('profile.show', compact('user', 'otpSent'));
    }

    /**
     * Update user's name and email address.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('profile.show')
            ->with('profile_status', 'Profile details updated successfully.');
    }

    /**
     * Send a 6-digit OTP to user's email for password reset.
     */
    public function sendPasswordOtp(Request $request): JsonResponse|RedirectResponse
    {
        @file_put_contents('/tmp/otp_debug.log', "ENTER sendPasswordOtp\n");
        /** @var User $user */
        $user = Auth::user();
        @file_put_contents('/tmp/otp_debug.log', "USER: " . ($user ? $user->email : 'null') . "\n", FILE_APPEND);

        $throttleKey = 'send-password-otp:' . $user->id;
        $ipThrottleKey = 'send-password-otp-ip:' . ($request->ip() ?: 'unknown');
        $cooldownKey = 'send-password-otp-cooldown:' . $user->id;

        // Rate limiting check: max 5 requests per 15 minutes
        if (RateLimiter::tooManyAttempts($throttleKey, 5) || RateLimiter::tooManyAttempts($ipThrottleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $msg = "Too many OTP requests. Please wait " . ceil($seconds / 60) . " minute(s) before trying again.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 429);
            }
            return back()->with('otp_error', $msg);
        }

        // Cooldown check: 30 seconds
        if (RateLimiter::tooManyAttempts($cooldownKey, 1)) {
            $remaining = RateLimiter::availableIn($cooldownKey);
            $msg = "Please wait {$remaining} second(s) before requesting another code.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 429);
            }
            return back()->with('otp_error', $msg);
        }

        RateLimiter::hit($throttleKey, 900);
        RateLimiter::hit($ipThrottleKey, 900);
        RateLimiter::hit($cooldownKey, 30);

        // Reset verify attempts for fresh OTP
        RateLimiter::clear('verify-password-otp:' . $user->id);

        $otp = (string) random_int(100000, 999999);
        @file_put_contents('/tmp/otp_debug.log', "OTP GENERATED\n", FILE_APPEND);

        // Store salted hash of OTP in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );
        @file_put_contents('/tmp/otp_debug.log', "DB TOKEN STORED\n", FILE_APPEND);

        session()->put('password_otp_sent', true);
        session()->put('password_otp_time', time());

        try {
            if (app()->environment('testing')) {
                Mail::to($user->email)->send(new PasswordResetOtpMail($user, $otp));
            } else {
                $htmlBody = view('emails.password-reset-otp', ['user' => $user, 'otp' => $otp])->render();
                $subject = "Your Password Reset Code: {$otp} — CIT Accounts";
                $result = \App\Services\CurlMailService::send($user->email, $subject, $htmlBody, $user->name);

                if (! $result['success']) {
                    throw new \Exception($result['message']);
                }
            }

            $msg = "Verification code sent to {$user->email}. Please check your inbox.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                ]);
            }

            return redirect()->route('profile.show')
                ->with('otp_status', $msg);

        } catch (Throwable $e) {
            $errorMsg = 'Failed to dispatch email. Please verify SMTP settings in System Settings: ' . $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }

            return back()->with('otp_error', $errorMsg);
        }
    }

    /**
     * Verify OTP and update password without requiring the old password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'otp' => ['required', 'string', 'digits:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $verifyKey = 'verify-password-otp:' . $user->id;

        // Check if exceeded max failed attempts (5 max)
        if (RateLimiter::attempts($verifyKey) >= 5) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            session()->forget(['password_otp_sent', 'password_otp_time']);
            RateLimiter::clear($verifyKey);

            return back()->withInput()->with('password_error', 'Too many failed verification attempts. For your security, this code has been invalidated. Please request a new code.');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if (! $record) {
            return back()->withInput()->with('password_error', 'No active OTP verification request found. Please request a new code.');
        }

        // Check 10 minute expiry
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            session()->forget(['password_otp_sent', 'password_otp_time']);
            RateLimiter::clear($verifyKey);

            return back()->withInput()->with('password_error', 'Your verification code has expired (valid for 10 minutes). Please request a new one.');
        }

        // Verify OTP match
        if (! Hash::check($validated['otp'], $record->token)) {
            RateLimiter::hit($verifyKey, 600);
            $remainingAttempts = 5 - RateLimiter::attempts($verifyKey);

            if ($remainingAttempts <= 0) {
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                session()->forget(['password_otp_sent', 'password_otp_time']);
                RateLimiter::clear($verifyKey);

                return back()->withInput()->with('password_error', 'Invalid code. Maximum attempts exceeded (5). This verification code has been invalidated.');
            }

            return back()->withInput()->with('password_error', "The 6-digit verification code you entered is invalid. ({$remainingAttempts} attempt(s) remaining)");
        }

        // Clear verification attempts upon success
        RateLimiter::clear($verifyKey);

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Invalidate token and session
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        session()->forget(['password_otp_sent', 'password_otp_time']);

        return redirect()->route('profile.show')
            ->with('password_status', 'Password updated successfully! Your new password is now active.');
    }
}
