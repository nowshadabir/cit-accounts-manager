<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * List of keys whose values must be encrypted at rest in the database.
     */
    public const SENSITIVE_KEYS = [
        'mail_password',
        'ftp_password',
    ];

    /**
     * Get a setting by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting || $setting->value === null) {
            return $default;
        }

        if (in_array($key, self::SENSITIVE_KEYS, true)) {
            try {
                return \Illuminate\Support\Facades\Crypt::decryptString($setting->value);
            } catch (\Throwable) {
                // Fallback for previously unencrypted legacy values
                return $setting->value;
            }
        }

        return $setting->value;
    }

    /**
     * Set a setting key and value.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): self
    {
        $storedValue = $value;

        if ($value !== null && $value !== '' && in_array($key, self::SENSITIVE_KEYS, true)) {
            $storedValue = \Illuminate\Support\Facades\Crypt::encryptString((string) $value);
        }

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'group' => $group]
        );
    }

    /**
     * Retrieve all settings belonging to a group as key-value pairs.
     */
    public static function getGroup(string $group): array
    {
        $settings = static::where('group', $group)->get();
        $result = [];

        foreach ($settings as $setting) {
            $val = $setting->value;
            if ($val !== null && in_array($setting->key, self::SENSITIVE_KEYS, true)) {
                try {
                    $val = \Illuminate\Support\Facades\Crypt::decryptString($val);
                } catch (\Throwable) {
                    // Legacy unencrypted
                }
            }
            $result[$setting->key] = $val;
        }

        return $result;
    }

    /**
     * Set multiple settings at once.
     */
    public static function setMany(array $settings, string $group = 'general'): void
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value, $group);
        }
    }
}
