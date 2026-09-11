<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class FtpStorageService
{
    /**
     * Upload a file to Namecheap FTP storage (with graceful local fallback).
     */
    public function upload(UploadedFile $file, string $subfolder = 'vouchers'): array
    {
        $originalFilename = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $cleanName = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $uniqueFilename = $cleanName . '_' . time() . '_' . Str::random(6) . '.' . $extension;
        $subfolder = trim($subfolder, '/');
        $relativeStoragePath = ($subfolder ? "{$subfolder}/" : '') . $uniqueFilename;

        $host = Setting::get('ftp_host');
        $port = (int) Setting::get('ftp_port', 21);
        $username = Setting::get('ftp_username');
        $password = Setting::get('ftp_password');
        $root = Setting::get('ftp_root', '/');
        $passive = (bool) Setting::get('ftp_passive', '1');
        $useSsl = (bool) Setting::get('ftp_ssl', '0');
        $baseUrl = rtrim(Setting::get('ftp_base_url', ''), '/');

        // Check if FTP is configured and curl is available
        if (!empty($host) && !empty($username) && !empty($password) && function_exists('curl_init')) {
            $localFilePath = $file->getRealPath() ?: $file->getPathname();
            $fileStream = @fopen($localFilePath, 'r');

            if ($fileStream) {
                try {
                    $prefix = (!empty($root) && $root !== '/') ? trim($root, '/') . '/' : '';
                    $targetPath = $prefix . $relativeStoragePath;
                    $scheme = $useSsl ? 'ftps' : 'ftp';
                    $url = "{$scheme}://{$host}:{$port}/" . ltrim($targetPath, '/');

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
                    curl_setopt($ch, CURLOPT_UPLOAD, true);
                    curl_setopt($ch, CURLOPT_INFILE, $fileStream);
                    curl_setopt($ch, CURLOPT_INFILESIZE, (int) filesize($localFilePath));
                    curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS, 1);
                    curl_setopt($ch, CURLOPT_FTP_USE_EPSV, $passive);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    if ($useSsl) {
                        curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_TRY);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    }

                    $execResult = curl_exec($ch);
                    $curlErr = curl_error($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                    curl_close($ch);
                    @fclose($fileStream);

                    if ($execResult !== false && ($httpCode == 226 || $httpCode == 250 || $httpCode == 200 || empty($curlErr))) {
                        $publicUrl = !empty($baseUrl) ? "{$baseUrl}/{$relativeStoragePath}" : null;

                        return [
                            'success' => true,
                            'storage_driver' => 'ftp',
                            'path' => $relativeStoragePath,
                            'url' => $publicUrl,
                            'filename' => $originalFilename,
                        ];
                    }

                    Log::warning("cURL FTP Upload failed: {$curlErr} (Code: {$httpCode}). Falling back to local storage.");
                } catch (Throwable $e) {
                    if (is_resource($fileStream)) {
                        @fclose($fileStream);
                    }
                    Log::warning('cURL FTP Upload error: ' . $e->getMessage() . '. Falling back to local storage.');
                }
            }
        }

        // Local Storage Fallback
        try {
            $localDiskPath = $file->storeAs("uploads/{$subfolder}", $uniqueFilename, 'public');
            $localUrl = Storage::disk('public')->url($localDiskPath);

            return [
                'success' => true,
                'storage_driver' => 'local',
                'path' => $localDiskPath,
                'url' => $localUrl,
                'filename' => $originalFilename,
            ];
        } catch (Throwable $e) {
            Log::error('Local fallback storage failed: ' . $e->getMessage());

            return [
                'success' => false,
                'storage_driver' => 'none',
                'path' => null,
                'url' => null,
                'filename' => $originalFilename,
            ];
        }
    }

    /**
     * Delete a file from remote FTP or local storage.
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $host = Setting::get('ftp_host');
        $port = (int) Setting::get('ftp_port', 21);
        $username = Setting::get('ftp_username');
        $password = Setting::get('ftp_password');
        $root = Setting::get('ftp_root', '/');
        $useSsl = (bool) Setting::get('ftp_ssl', '0');

        if (!empty($host) && !empty($username) && !empty($password) && function_exists('curl_init')) {
            try {
                $prefix = (!empty($root) && $root !== '/') ? trim($root, '/') . '/' : '';
                $targetPath = $prefix . $path;
                $scheme = $useSsl ? 'ftps' : 'ftp';
                $url = "{$scheme}://{$host}:{$port}/";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
                curl_setopt($ch, CURLOPT_QUOTE, ['DELE ' . ltrim($targetPath, '/')]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
                curl_setopt($ch, CURLOPT_TIMEOUT, 8);

                if ($useSsl) {
                    curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_TRY);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                }

                $res = curl_exec($ch);
                curl_close($ch);

                if ($res !== false) {
                    return true;
                }
            } catch (Throwable) {
                // Ignore and try local
            }
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Stream or download remote file content safely via cURL.
     */
    public function streamFile(string $path): ?string
    {
        $host = Setting::get('ftp_host');
        $port = (int) Setting::get('ftp_port', 21);
        $username = Setting::get('ftp_username');
        $password = Setting::get('ftp_password');
        $root = Setting::get('ftp_root', '/');
        $passive = (bool) Setting::get('ftp_passive', '1');
        $useSsl = (bool) Setting::get('ftp_ssl', '0');

        if (!empty($host) && !empty($username) && !empty($password) && function_exists('curl_init')) {
            try {
                $prefix = (!empty($root) && $root !== '/') ? trim($root, '/') . '/' : '';
                $targetPath = $prefix . $path;
                $scheme = $useSsl ? 'ftps' : 'ftp';
                $url = "{$scheme}://{$host}:{$port}/" . ltrim($targetPath, '/');

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FTP_USE_EPSV, $passive);

                if ($useSsl) {
                    curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_TRY);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                }

                $contents = curl_exec($ch);
                $errNo = curl_errno($ch);
                $curlErr = curl_error($ch);
                curl_close($ch);

                if ($errNo === 0 && $contents !== false) {
                    return $contents;
                }

                Log::warning("cURL FTP stream failed: {$curlErr} (Errno: {$errNo})");
            } catch (Throwable $e) {
                Log::warning('cURL FTP stream download failed: ' . $e->getMessage());
            }
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->get($path);
        }

        return null;
    }

    /**
     * Test FTP connection credentials and write capability.
     */
    public static function testConnection(string $host, int $port, string $username, string $password, string $root = '/', bool $passive = true, bool $useSsl = false): array
    {
        if (empty($host) || empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'FTP Host, Username, and Password are required.',
            ];
        }

        if (!function_exists('curl_init')) {
            return [
                'success' => false,
                'message' => 'PHP cURL extension is required for secure FTP connectivity.',
            ];
        }

        try {
            $prefix = (!empty($root) && $root !== '/') ? trim($root, '/') . '/' : '';
            $testFilename = 'verify_' . time() . '.txt';
            $targetPath = $prefix . $testFilename;
            $scheme = $useSsl ? 'ftps' : 'ftp';
            $url = "{$scheme}://{$host}:{$port}/" . ltrim($targetPath, '/');

            // Write verification file
            $payload = 'CIT Accounts Connection & Write Test';
            $stream = fopen('php://memory', 'r+');
            fwrite($stream, $payload);
            rewind($stream);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILE, $stream);
            curl_setopt($ch, CURLOPT_INFILESIZE, strlen($payload));
            curl_setopt($ch, CURLOPT_FTP_USE_EPSV, $passive);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if ($useSsl) {
                curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_TRY);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            }

            $exec = curl_exec($ch);
            $errNo = curl_errno($ch);
            $errMsg = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            fclose($stream);

            if ($errNo !== 0) {
                $safeErrMsg = !empty($password) ? str_replace($password, '********', $errMsg) : $errMsg;
                return [
                    'success' => false,
                    'message' => "FTP Connection Failed: {$safeErrMsg} (Code: {$errNo}). Verify host, username, password and passive mode.",
                ];
            }

            // Cleanup verification file
            $deleteUrl = "{$scheme}://{$host}:{$port}/";
            $delCh = curl_init($deleteUrl);
            curl_setopt($delCh, CURLOPT_USERPWD, "{$username}:{$password}");
            curl_setopt($delCh, CURLOPT_QUOTE, ['DELE ' . ltrim($targetPath, '/')]);
            curl_setopt($delCh, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($delCh, CURLOPT_TIMEOUT, 5);
            if ($useSsl) {
                curl_setopt($delCh, CURLOPT_USE_SSL, CURLUSESSL_TRY);
                curl_setopt($delCh, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($delCh, CURLOPT_SSL_VERIFYHOST, 0);
            }
            curl_exec($delCh);
            curl_close($delCh);

            return [
                'success' => true,
                'message' => "Successfully connected to Namecheap FTP ({$host})! Authentication & write permissions verified.",
            ];
        } catch (Throwable $e) {
            $msg = $e->getMessage();
            $safeMsg = !empty($password) ? str_replace($password, '********', $msg) : $msg;
            return [
                'success' => false,
                'message' => 'FTP Connection Exception: ' . $safeMsg,
            ];
        }
    }
}
