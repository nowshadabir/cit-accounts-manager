<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_settings(): void
    {
        $response = $this->get('/settings');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_settings(): void
    {
        $user = User::factory()->create([
            'role' => 'super admin',
        ]);

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
        $response->assertSee('System Settings');
        $response->assertSee('Gmail SMTP Configuration');
        $response->assertSee('Namecheap Shared Hosting FTP Storage');
    }

    public function test_can_save_gmail_smtp_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/settings/mail', [
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_encryption' => 'tls',
            'mail_username' => 'accounts@mycompany.com',
            'mail_password' => 'secretapppassword',
            'mail_from_address' => 'accounts@mycompany.com',
            'mail_from_name' => 'CIT Accounts Dept',
        ]);

        $response->assertRedirect('/settings');
        $response->assertSessionHas('mail_status');

        $this->assertEquals('smtp.gmail.com', Setting::get('mail_host'));
        $this->assertEquals('587', Setting::get('mail_port'));
        $this->assertEquals('accounts@mycompany.com', Setting::get('mail_username'));
        $this->assertEquals('secretapppassword', Setting::get('mail_password'));
        $this->assertEquals('tls', Setting::get('mail_encryption'));
    }

    public function test_can_save_namecheap_ftp_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/settings/ftp', [
            'ftp_host' => 'ftp.mynamecheapdomain.com',
            'ftp_port' => '21',
            'ftp_username' => 'cpanel_user',
            'ftp_password' => 'ftppassword123',
            'ftp_root' => '/public_html/uploads',
            'ftp_passive' => '1',
            'ftp_ssl' => '0',
            'ftp_base_url' => 'https://mynamecheapdomain.com/uploads',
        ]);

        $response->assertRedirect('/settings');
        $response->assertSessionHas('ftp_status');

        $this->assertEquals('ftp.mynamecheapdomain.com', Setting::get('ftp_host'));
        $this->assertEquals('21', Setting::get('ftp_port'));
        $this->assertEquals('cpanel_user', Setting::get('ftp_username'));
        $this->assertEquals('ftppassword123', Setting::get('ftp_password'));
        $this->assertEquals('/public_html/uploads', Setting::get('ftp_root'));
        $this->assertEquals('1', Setting::get('ftp_passive'));
        $this->assertEquals('https://mynamecheapdomain.com/uploads', Setting::get('ftp_base_url'));
    }

    public function test_test_ftp_fails_gracefully_when_unconfigured(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/settings/ftp/test');

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_test_mail_fails_gracefully_when_unconfigured(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/settings/mail/test', [
            'test_email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }
}
