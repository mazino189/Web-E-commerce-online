<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
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

    public function test_admin_can_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/categories');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/categories', [
                'name' => 'New Category',
                'slug' => 'new-category',
            ]);

        $response->assertStatus(201)
            ->assertJson(['data' => ['name' => 'New Category']]);

        $this->assertDatabaseHas('categories', ['slug' => 'new-category']);
    }

    public function test_admin_can_show_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $category->id]]);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/categories/{$category->id}", [
                'name' => 'Updated Category',
                'slug' => 'updated-category',
            ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Updated Category']]);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_normal_user_cannot_access_admin_categories(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/categories');

        $response->assertStatus(403);
    }
}
