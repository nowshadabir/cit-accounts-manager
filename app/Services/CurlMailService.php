<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CurlMailService
{
    /**
     * Send an HTML email via cURL SMTP transport.
     */
    public static function send(string $toEmail, string $subject, string $htmlBody, ?string $toName = null): array
    {
        $host = Setting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com'));
        $port = (int) Setting::get('mail_port', config('mail.mailers.smtp.port', 587));
        $username = Setting::get('mail_username', config('mail.mailers.smtp.username'));
        $password = Setting::get('mail_password', config('mail.mailers.smtp.password'));
        $encryption = Setting::get('mail_encryption', config('mail.mailers.smtp.encryption', 'tls'));
        $fromAddress = Setting::get('mail_from_address', config('mail.from.address', $username));
        $fromName = Setting::get('mail_from_name', config('mail.from.name', 'CIT Accounts Management'));

        if (empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'SMTP username and password are not configured in System Settings.',
            ];
        }

        $cleanPassword = str_replace(' ', '', (string) $password);
        $scheme = ($port === 465 || $encryption === 'ssl') ? 'smtps' : 'smtp';
        $url = "{$scheme}://{$host}:{$port}";

        try {
            $messageId = '<' . time() . '.' . Str::random(16) . '@' . (parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'cit-accounts.local') . '>';
            $date = date('r');

            $headers = [
                "Date: {$date}",
                "Message-ID: {$messageId}",
                "From: {$fromName} <{$fromAddress}>",
                "To: " . ($toName ? "{$toName} <{$toEmail}>" : "<{$toEmail}>"),
                "Subject: {$subject}",
                "MIME-Version: 1.0",
                "Content-Type: text/html; charset=UTF-8",
                "Content-Transfer-Encoding: base64",
                "X-Mailer: CIT-Accounts-Mailer",
            ];

            $rawPayload = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($htmlBody)) . "\r\n";

            $stream = fopen('php://memory', 'r+');
            fwrite($stream, $rawPayload);
            rewind($stream);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$cleanPassword}");
            if ($encryption !== 'none') {
                curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_ALL);
            }
            curl_setopt($ch, CURLOPT_MAIL_FROM, "<{$fromAddress}>");
            curl_setopt($ch, CURLOPT_MAIL_RCPT, ["<{$toEmail}>"]);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILE, $stream);
            curl_setopt($ch, CURLOPT_INFILESIZE, strlen($rawPayload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $exec = curl_exec($ch);
            $errNo = curl_errno($ch);
            $errMsg = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            fclose($stream);

            if ($errNo !== 0) {
                $safeErrMsg = str_replace($cleanPassword, '********', $errMsg);
                Log::error("cURL SMTP sending failed: {$safeErrMsg} (Code: {$errNo})");

                return [
                    'success' => false,
                    'message' => "SMTP Error ({$errNo}): {$safeErrMsg}",
                ];
            }

            return [
                'success' => true,
                'message' => "Email sent successfully to {$toEmail}.",
            ];

        } catch (Throwable $e) {
            $safeMsg = str_replace($cleanPassword, '********', $e->getMessage());
            Log::error("cURL SMTP exception: {$safeMsg}");

            return [
                'success' => false,
                'message' => 'Mail Exception: ' . $safeMsg,
            ];
        }
    }
}
