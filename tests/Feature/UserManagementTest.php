<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_users_page(): void
    {
        $response = $this->get('/users');

        $response->assertRedirect('/login');
    }

    public function test_non_super_admin_cannot_access_users_page(): void
    {
        $user = User::factory()->create([
            'role' => 'accountant',
        ]);

        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(403);

        $dashResponse = $this->actingAs($user)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertDontSee('accountant');
    }

    public function test_super_admin_can_view_users_page(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'kazi abu sayed',
            'email' => 'ksayed118@gmail.com',
            'role' => 'super admin',
        ]);

        $response = $this->actingAs($superAdmin)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Users Management');
        $response->assertSee('kazi abu sayed');
    }

    public function test_super_admin_can_create_new_user_with_role(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        $response = $this->actingAs($superAdmin)->post('/users', [
            'name' => 'Tanvir Ahmed',
            'email' => 'tanvir@example.com',
            'password' => 'secret123',
            'role' => 'edit',
        ]);

        $response->assertRedirect('/users');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('users', [
            'name' => 'Tanvir Ahmed',
            'email' => 'tanvir@example.com',
            'role' => 'edit',
        ]);

        $createdUser = User::where('email', 'tanvir@example.com')->first();
        $this->assertTrue(Hash::check('secret123', $createdUser->password));
    }

    public function test_super_admin_can_update_user_details_and_role(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'view',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($superAdmin)->put("/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'edit',
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect('/users');
        $response->assertSessionHas('status');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('edit', $user->role);
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_super_admin_can_update_user_without_modifying_password(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Member One',
            'email' => 'member1@example.com',
            'role' => 'view',
            'password' => Hash::make('original-secret'),
        ]);

        $response = $this->actingAs($superAdmin)->put("/users/{$user->id}", [
            'name' => 'Member Updated',
            'email' => 'member1@example.com',
            'role' => 'edit',
            'password' => '',
        ]);

        $response->assertRedirect('/users');
        $user->refresh();
        $this->assertEquals('Member Updated', $user->name);
        $this->assertEquals('edit', $user->role);
        $this->assertTrue(Hash::check('original-secret', $user->password));
    }

    public function test_non_super_admin_cannot_create_user(): void
    {
        $editUser = User::factory()->create([
            'role' => 'edit',
        ]);

        $response = $this->actingAs($editUser)->post('/users', [
            'name' => 'Unauthorized User',
            'email' => 'unauth@example.com',
            'password' => 'secret123',
            'role' => 'view',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'unauth@example.com']);
    }

    public function test_non_super_admin_cannot_update_user(): void
    {
        $viewUser = User::factory()->create([
            'role' => 'view',
        ]);

        $otherUser = User::factory()->create([
            'name' => 'Target User',
            'role' => 'view',
        ]);

        $response = $this->actingAs($viewUser)->put("/users/{$otherUser->id}", [
            'name' => 'Hacked Name',
            'email' => $otherUser->email,
            'role' => 'super admin',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('Target User', $otherUser->fresh()->name);
    }

    public function test_cannot_create_user_with_duplicate_email(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->actingAs($superAdmin)->post('/users', [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'password' => 'secret123',
            'role' => 'view',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_super_admin_cannot_delete_own_account(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        $response = $this->actingAs($superAdmin)->delete("/users/{$superAdmin->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);
    }

    public function test_super_admin_can_delete_other_user(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        $otherUser = User::factory()->create([
            'name' => 'Deletable User',
            'email' => 'deletable@example.com',
            'role' => 'view',
        ]);

        $response = $this->actingAs($superAdmin)->delete("/users/{$otherUser->id}");

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
    }
}
