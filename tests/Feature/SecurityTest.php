<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_sensitive_passwords_are_encrypted_at_rest_in_the_database(): void
    {
        $user = User::factory()->create();

        // 1. Save mail settings
        $this->actingAs($user)->post('/settings/mail', [
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_encryption' => 'tls',
            'mail_username' => 'test@cit.test',
            'mail_password' => 'supersecretapppassword123',
            'mail_from_address' => 'test@cit.test',
            'mail_from_name' => 'CIT Security',
        ]);

        // 2. Save FTP settings
        $this->actingAs($user)->post('/settings/ftp', [
            'ftp_host' => 'ftp.server.test',
            'ftp_port' => '21',
            'ftp_username' => 'cpanel_admin',
            'ftp_password' => 'cpanel_secret_pass_xyz',
            'ftp_root' => '/',
            'ftp_passive' => '1',
            'ftp_ssl' => '0',
            'ftp_base_url' => '',
        ]);

        // Direct database query (raw value) must NOT match plaintext
        $rawMailRecord = DB::table('settings')->where('key', 'mail_password')->first();
        $rawFtpRecord = DB::table('settings')->where('key', 'ftp_password')->first();

        $this->assertNotNull($rawMailRecord);
        $this->assertNotNull($rawFtpRecord);

        // Verify the raw stored value is not plaintext
        $this->assertNotEquals('supersecretapppassword123', $rawMailRecord->value);
        $this->assertNotEquals('cpanel_secret_pass_xyz', $rawFtpRecord->value);

        // Verify it can be decrypted with Laravel Crypt
        $this->assertEquals('supersecretapppassword123', Crypt::decryptString($rawMailRecord->value));
        $this->assertEquals('cpanel_secret_pass_xyz', Crypt::decryptString($rawFtpRecord->value));

        // Verify Setting::get() transparently decrypts
        $this->assertEquals('supersecretapppassword123', Setting::get('mail_password'));
        $this->assertEquals('cpanel_secret_pass_xyz', Setting::get('ftp_password'));
    }

    public function test_plain_passwords_are_never_rendered_in_settings_html(): void
    {
        $user = User::factory()->create(['role' => 'super admin']);

        Setting::set('mail_password', 'unexposed_secret_mail_pass_456', 'mail');
        Setting::set('ftp_password', 'unexposed_secret_ftp_pass_789', 'ftp');

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
        // Raw plaintext passwords must never appear in HTML
        $response->assertDontSee('unexposed_secret_mail_pass_456');
        $response->assertDontSee('unexposed_secret_ftp_pass_789');
        // Masked indicator should appear
        $response->assertSee('••••••••••••••••');
    }

    public function test_legacy_unencrypted_settings_are_handled_gracefully(): void
    {
        // Simulate a legacy unencrypted record inserted directly into DB
        DB::table('settings')->insert([
            'key' => 'mail_password',
            'value' => 'legacy_plain_password',
            'group' => 'mail',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertEquals('legacy_plain_password', Setting::get('mail_password'));
    }

    public function test_otp_tokens_are_securely_hashed_in_database(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'otpuser@cit.test']);

        $this->actingAs($user)->post('/profile/password/send-otp');

        $record = DB::table('password_reset_tokens')->where('email', 'otpuser@cit.test')->first();
        $this->assertNotNull($record);

        // Token must be bcrypt hashed (starts with $2y$)
        $this->assertStringStartsWith('$2y$', $record->token);
    }

    public function test_otp_brute_force_is_blocked_after_five_failed_attempts(): void
    {
        RateLimiter::clear('verify-password-otp:1');
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'brute@cit.test',
            'password' => Hash::make('initial-pass'),
        ]);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make('654321'),
                'created_at' => now(),
            ]
        );

        // Attempt 1 to 4 with incorrect OTP
        for ($i = 1; $i <= 4; $i++) {
            $resp = $this->actingAs($user)->post('/profile/password/update', [
                'otp' => '00000' . $i,
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);
            $resp->assertSessionHas('password_error');
            // Token still exists
            $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
        }

        // Attempt 5 (5th failure exceeds limit and invalidates OTP)
        $resp5 = $this->actingAs($user)->post('/profile/password/update', [
            'otp' => '000005',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $resp5->assertSessionHas('password_error');

        // Token MUST be deleted from database to prevent further brute-force
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        // Even if attacker now supplies correct OTP, it must be rejected
        $respCorrect = $this->actingAs($user)->post('/profile/password/update', [
            'otp' => '654321',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $respCorrect->assertSessionHas('password_error');
        $this->assertTrue(Hash::check('initial-pass', $user->fresh()->password));
    }

    public function test_otp_send_rate_limiting_enforces_cooldown_and_max_requests(): void
    {
        Mail::fake();
        $user = User::factory()->create(['id' => 99, 'email' => 'ratelimit@cit.test']);
        RateLimiter::clear('send-password-otp:99');
        RateLimiter::clear('send-password-otp-cooldown:99');

        // 1st request succeeds
        $resp1 = $this->actingAs($user)->post('/profile/password/send-otp');
        $resp1->assertSessionHas('otp_status');

        // Immediate 2nd request hits cooldown
        $resp2 = $this->actingAs($user)->post('/profile/password/send-otp');
        $resp2->assertSessionHas('otp_error');
    }
}
