<?php

namespace Tests\Feature;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');

        $updateResponse = $this->put('/profile', ['name' => 'Hacker', 'email' => 'hacker@test.com']);
        $updateResponse->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Kazi Sayed',
            'email' => 'sayed@cit.test',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Account Settings & Profile', false);
        $response->assertSee('Kazi Sayed');
        $response->assertSee('sayed@cit.test');
        $response->assertSee('Reset Password');
        $response->assertSee('No current password required');
    }

    public function test_user_can_update_profile_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@cit.test',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@cit.test',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('profile_status');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@cit.test', $user->email);
    }

    public function test_user_cannot_update_to_duplicate_email(): void
    {
        $userA = User::factory()->create(['email' => 'userA@cit.test']);
        $userB = User::factory()->create(['email' => 'userB@cit.test']);

        $response = $this->actingAs($userA)->put('/profile', [
            'name' => 'User A',
            'email' => 'userB@cit.test',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('userA@cit.test', $userA->fresh()->email);
    }

    public function test_user_can_request_password_reset_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'member@cit.test']);

        $response = $this->actingAs($user)->post('/profile/password/send-otp');

        $response->assertRedirect('/profile');
        $response->assertSessionHas('otp_status');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'member@cit.test',
        ]);

        Mail::assertSent(PasswordResetOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo('member@cit.test') && strlen($mail->otp) === 6;
        });
    }

    public function test_user_cannot_reset_password_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'member@cit.test',
            'password' => Hash::make('original-password'),
        ]);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make('123456'),
                'created_at' => now(),
            ]
        );

        $response = $this->actingAs($user)->post('/profile/password/update', [
            'otp' => '999999', // wrong OTP
            'password' => 'new-secret-123',
            'password_confirmation' => 'new-secret-123',
        ]);

        $response->assertSessionHas('password_error');
        $this->assertTrue(Hash::check('original-password', $user->fresh()->password));
    }

    public function test_user_cannot_reset_password_with_expired_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'member@cit.test',
            'password' => Hash::make('original-password'),
        ]);

        // Insert OTP created 15 minutes ago (expired)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make('123456'),
                'created_at' => Carbon::now()->subMinutes(15),
            ]
        );

        $response = $this->actingAs($user)->post('/profile/password/update', [
            'otp' => '123456',
            'password' => 'new-secret-123',
            'password_confirmation' => 'new-secret-123',
        ]);

        $response->assertSessionHas('password_error');
        $this->assertTrue(Hash::check('original-password', $user->fresh()->password));
    }

    public function test_user_can_successfully_reset_password_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'member@cit.test',
            'password' => Hash::make('old-password-123'),
        ]);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make('654321'),
                'created_at' => now(),
            ]
        );

        $response = $this->actingAs($user)->post('/profile/password/update', [
            'otp' => '654321',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('password_status');

        $user->refresh();
        $this->assertTrue(Hash::check('brand-new-password', $user->password));

        // Ensure OTP token is consumed
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'member@cit.test',
        ]);
    }
}
