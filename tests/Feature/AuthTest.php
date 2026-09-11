<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_login_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Forgot password?');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'ksayed118@gmail.com',
            'password' => 'kazisayed',
            'role' => 'super admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'ksayed118@gmail.com',
            'password' => 'kazisayed',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'ksayed118@gmail.com',
            'password' => 'kazisayed',
            'role' => 'super admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'ksayed118@gmail.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_unauthenticated_users_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_view_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'kazi abu sayed',
            'email' => 'ksayed118@gmail.com',
            'role' => 'super admin',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('kazi abu sayed');
        $response->assertDontSee('super admin');
        $response->assertSee('Dashboard');
        $response->assertSee('Entry Form');
        $response->assertSee('Report Export');
        $response->assertSee('Users');
        $response->assertSee('Settings');
        $response->assertSee('Profile');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
