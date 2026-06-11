<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBrandTest extends TestCase
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

    public function test_admin_can_list_brands(): void
    {
        Brand::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/brands');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_admin_can_create_brand(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/brands', [
                'name' => 'New Brand',
                'slug' => 'new-brand',
            ]);

        $response->assertStatus(201)
            ->assertJson(['data' => ['name' => 'New Brand']]);

        $this->assertDatabaseHas('brands', ['slug' => 'new-brand']);
    }

    public function test_admin_can_show_brand(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $brand->id]]);
    }

    public function test_admin_can_update_brand(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/brands/{$brand->id}", [
                'name' => 'Updated Brand',
                'slug' => 'updated-brand',
            ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Updated Brand']]);

        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'name' => 'Updated Brand']);
    }

    public function test_admin_can_delete_brand(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/brands/{$brand->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_normal_user_cannot_access_admin_brands(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/brands');

        $response->assertStatus(403);
    }
}
