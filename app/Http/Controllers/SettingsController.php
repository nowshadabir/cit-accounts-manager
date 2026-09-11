<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\FtpStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class SettingsController extends Controller
{
    /**
     * Display the settings page with current values.
     */
    public function index(): View
    {
        $rawMailPass = Setting::get('mail_password');
        $rawFtpPass = Setting::get('ftp_password');

        $mailSettings = [
            'mail_mailer' => Setting::get('mail_mailer', 'smtp'),
            'mail_host' => Setting::get('mail_host', 'smtp.gmail.com'),
            'mail_port' => Setting::get('mail_port', '587'),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_password' => !empty($rawMailPass) ? '••••••••••••••••' : '',
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
            'mail_from_address' => Setting::get('mail_from_address', ''),
            'mail_from_name' => Setting::get('mail_from_name', 'CIT Accounts'),
        ];

        $ftpSettings = [
            'ftp_host' => Setting::get('ftp_host', ''),
            'ftp_port' => Setting::get('ftp_port', '21'),
            'ftp_username' => Setting::get('ftp_username', ''),
            'ftp_password' => !empty($rawFtpPass) ? '••••••••••••••••' : '',
            'ftp_root' => Setting::get('ftp_root', '/'),
            'ftp_passive' => Setting::get('ftp_passive', '1'),
            'ftp_ssl' => Setting::get('ftp_ssl', '0'),
            'ftp_base_url' => Setting::get('ftp_base_url', ''),
        ];

        return view('settings.index', compact('mailSettings', 'ftpSettings'));
    }

    /**
     * Save Gmail SMTP settings.
     */
    public function updateMail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mail_host' => ['required', 'string'],
            'mail_port' => ['required', 'numeric'],
            'mail_username' => ['required', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['required', 'in:tls,ssl,none'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string'],
        ]);

        Setting::set('mail_mailer', 'smtp', 'mail');
        Setting::set('mail_host', $validated['mail_host'], 'mail');
        Setting::set('mail_port', $validated['mail_port'], 'mail');
        Setting::set('mail_username', $validated['mail_username'], 'mail');
        
        if (!empty($validated['mail_password']) && !str_contains($validated['mail_password'], '••••')) {
            Setting::set('mail_password', str_replace(' ', '', $validated['mail_password']), 'mail');
        }

        Setting::set('mail_encryption', $validated['mail_encryption'] === 'none' ? null : $validated['mail_encryption'], 'mail');
        Setting::set('mail_from_address', $validated['mail_from_address'], 'mail');
        Setting::set('mail_from_name', $validated['mail_from_name'], 'mail');

        return redirect()->route('settings.index')
            ->with('mail_status', 'Gmail SMTP settings saved successfully.');
    }

    /**
     * Send a test email to verify SMTP configuration.
     */
    public function testMail(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        $recipient = $request->input('test_email');
        $host = $request->input('mail_host') ?: Setting::get('mail_host', 'smtp.gmail.com');
        $port = (int) ($request->input('mail_port') ?: Setting::get('mail_port', '587'));
        $username = $request->input('mail_username') ?: Setting::get('mail_username');
        
        $reqPass = $request->input('mail_password');
        $rawPassword = (!empty($reqPass) && !str_contains($reqPass, '••••')) 
            ? $reqPass 
            : Setting::get('mail_password');

        $password = str_replace(' ', '', (string) $rawPassword);

        $encryption = $request->input('mail_encryption') ?: Setting::get('mail_encryption', 'tls');
        $fromAddress = $request->input('mail_from_address') ?: Setting::get('mail_from_address', $username);
        $fromName = $request->input('mail_from_name') ?: Setting::get('mail_from_name', 'CIT Accounts Management');

        if (empty($username) || empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Please configure Gmail username and App Password first.',
            ], 422);
        }

        try {
            if (app()->environment('testing')) {
                Mail::raw("Test", function ($message) use ($recipient, $fromAddress, $fromName) {
                    $message->to($recipient)->from($fromAddress, $fromName)->subject('Test');
                });
            } else {
                $testHtml = "<p>Hello,</p><p>This is a test email sent from <strong>CIT Accounts Management System</strong> to verify your Gmail SMTP configuration.</p><p>Time: " . now()->toDayDateTimeString() . "</p>";
                $result = \App\Services\CurlMailService::send($recipient, 'Gmail SMTP Test Connection — CIT Accounts', $testHtml);

                if (! $result['success']) {
                    return response()->json($result, 500);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Test email sent successfully to {$recipient}!",
            ]);

        } catch (Throwable $e) {
            $rawMsg = $e->getMessage();
            $safeMsg = !empty($password) ? str_replace($password, '********', $rawMsg) : $rawMsg;

            return response()->json([
                'success' => false,
                'message' => 'SMTP Connection Failed: ' . $safeMsg,
            ], 500);
        }
    }

    /**
     * Save Namecheap Shared Hosting FTP settings.
     */
    public function updateFtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ftp_host' => ['required', 'string'],
            'ftp_port' => ['required', 'numeric'],
            'ftp_username' => ['required', 'string'],
            'ftp_password' => ['nullable', 'string'],
            'ftp_root' => ['nullable', 'string'],
            'ftp_passive' => ['nullable', 'boolean'],
            'ftp_ssl' => ['nullable', 'boolean'],
            'ftp_base_url' => ['nullable', 'string'],
        ]);

        Setting::set('ftp_host', $validated['ftp_host'], 'ftp');
        Setting::set('ftp_port', $validated['ftp_port'], 'ftp');
        Setting::set('ftp_username', $validated['ftp_username'], 'ftp');
        
        if (!empty($validated['ftp_password']) && !str_contains($validated['ftp_password'], '••••')) {
            Setting::set('ftp_password', $validated['ftp_password'], 'ftp');
        }

        Setting::set('ftp_root', $validated['ftp_root'] ?? '/', 'ftp');
        Setting::set('ftp_passive', $request->boolean('ftp_passive') ? '1' : '0', 'ftp');
        Setting::set('ftp_ssl', $request->boolean('ftp_ssl') ? '1' : '0', 'ftp');
        Setting::set('ftp_base_url', rtrim($validated['ftp_base_url'] ?? '', '/'), 'ftp');

        return redirect()->route('settings.index')
            ->with('ftp_status', 'Namecheap FTP storage settings saved successfully.');
    }

    /**
     * Test Namecheap FTP connection and folder write permissions.
     */
    public function testFtp(Request $request): JsonResponse
    {
        $host = $request->input('ftp_host') ?: Setting::get('ftp_host');
        $port = (int) ($request->input('ftp_port') ?: Setting::get('ftp_port', '21'));
        $username = $request->input('ftp_username') ?: Setting::get('ftp_username');

        $reqPass = $request->input('ftp_password');
        $password = (!empty($reqPass) && !str_contains($reqPass, '••••')) 
            ? $reqPass 
            : Setting::get('ftp_password');

        $root = $request->has('ftp_root') ? $request->input('ftp_root') : Setting::get('ftp_root', '/');
        $passive = $request->has('ftp_passive') ? $request->boolean('ftp_passive') : (bool) Setting::get('ftp_passive', '1');
        $useSsl = $request->has('ftp_ssl') ? $request->boolean('ftp_ssl') : (bool) Setting::get('ftp_ssl', '0');

        $result = FtpStorageService::testConnection(
            (string) $host,
            $port,
            (string) $username,
            (string) $password,
            (string) $root,
            $passive,
            $useSsl
        );

        if (!empty($password) && isset($result['message'])) {
            $result['message'] = str_replace($password, '********', $result['message']);
        }

        $statusCode = $result['success'] ? 200 : ($result['message'] === 'FTP Host, Username, and Password are required.' ? 422 : 500);

        return response()->json($result, $statusCode);
    }
}
