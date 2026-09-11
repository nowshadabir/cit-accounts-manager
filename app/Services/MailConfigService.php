<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailConfigService
{
    /**
     * Apply stored Gmail SMTP settings dynamically if configured.
     */
    public static function applySettings(): void
    {
        $host = Setting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com'));
        $port = (int) Setting::get('mail_port', config('mail.mailers.smtp.port', 587));
        $username = Setting::get('mail_username', config('mail.mailers.smtp.username'));
        $password = Setting::get('mail_password', config('mail.mailers.smtp.password'));
        $encryption = Setting::get('mail_encryption', config('mail.mailers.smtp.encryption', 'tls'));
        $fromAddress = Setting::get('mail_from_address', config('mail.from.address', $username));
        $fromName = Setting::get('mail_from_name', config('mail.from.name', 'CIT Accounts Management'));

        if (!empty($username) && !empty($password)) {
            Mail::purge();
            Mail::purge('smtp');

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $host,
                'port' => $port,
                'encryption' => ($encryption === 'none' || empty($encryption)) ? null : $encryption,
                'username' => $username,
                'password' => str_replace(' ', '', (string) $password),
                'timeout' => 15,
            ]);

            if (!empty($fromAddress)) {
                Config::set('mail.from', [
                    'address' => $fromAddress,
                    'name' => $fromName ?: 'CIT Accounts Management',
                ]);
            }
        }
    }
}
