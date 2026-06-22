<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_admin_can_list_users()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/admin/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email', 'role', 'created_at']
                     ]
                 ]);
    }

    public function test_user_cannot_list_users()
    {
        $response = $this->actingAs($this->user)->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'role' => 'user'
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/admin/users', $userData);

        $response->assertStatus(201)
                 ->assertJsonPath('data.email', 'newuser@example.com')
                 ->assertJsonPath('data.role', 'user');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_admin_cannot_create_user_with_existing_email()
    {
        $userData = [
            'name' => 'New User',
            'email' => $this->user->email,
            'password' => 'password123',
            'role' => 'user'
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/admin/users', $userData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_admin_can_update_user()
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'admin'
        ];

        $response = $this->actingAs($this->admin)->putJson("/api/admin/users/{$this->user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Updated Name')
                 ->assertJsonPath('data.email', 'updated@example.com');

        $this->assertDatabaseHas('users', ['email' => 'updated@example.com', 'role' => 'admin']);
    }

    public function test_admin_cannot_update_user_to_existing_email()
    {
        $otherUser = User::factory()->create(['email' => 'other@example.com']);

        $updateData = [
            'name' => 'Updated Name',
            'email' => $otherUser->email,
            'role' => 'user'
        ];

        $response = $this->actingAs($this->admin)->putJson("/api/admin/users/{$this->user->id}", $updateData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_admin_can_delete_user()
    {
        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/users/{$this->user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    }

    public function test_admin_cannot_delete_themselves()
    {
        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/users/{$this->admin->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
