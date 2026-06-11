<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private Category $category;
    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->category = Category::factory()->create();
        $this->brand = Brand::factory()->create();
    }

    public function test_admin_can_list_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/products');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/products', [
                'name' => 'Test Product',
                'slug' => 'test-product',
                'description' => 'A test product',
                'price' => 29.99,
                'stock' => 10,
                'category_id' => $this->category->id,
                'brand_id' => $this->brand->id,
            ]);

        $response->assertStatus(201)
            ->assertJson(['data' => ['name' => 'Test Product']]);

        $this->assertDatabaseHas('products', ['slug' => 'test-product']);
    }

    public function test_admin_can_show_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $product->id]]);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/products/{$product->id}", [
                'name' => 'Updated Name',
                'slug' => 'updated-slug',
                'price' => 49.99,
                'stock' => 20,
                'category_id' => $this->category->id,
                'brand_id' => $this->brand->id,
            ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Updated Name']]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_normal_user_cannot_access_admin_products(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/products');

        $response->assertStatus(403);
    }
}
