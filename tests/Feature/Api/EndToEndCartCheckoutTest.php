<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EndToEndCartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private Category $category;
    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'user']);

        $this->category = Category::create([
            'name' => 'Kitchen Appliances',
            'slug' => 'kitchen-appliances',
            'description' => 'Test category',
        ]);

        $this->brand = Brand::create([
            'name' => 'TestBrand',
            'slug' => 'testbrand',
            'description' => 'Test brand',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'E2E Test Blender',
            'slug' => 'e2e-test-blender',
            'description' => 'A blender for E2E testing.',
            'price' => 249900,
            'stock' => 50,
            'image' => 'default-product.jpg',
            'status' => true,
        ]);
    }

    public function test_complete_shopping_journey(): void
    {
        // 1. Authentication
        Sanctum::actingAs($this->user);

        // 2. Add to cart
        $cartResponse = $this->postJson('/api/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $cartResponse->assertStatus(201)
            ->assertJson([
                'data' => [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                ],
            ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        // 3. View cart
        $viewCartResponse = $this->getJson('/api/cart');

        $viewCartResponse->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'product_id', 'quantity', 'product']]])
            ->assertJson(['data' => [
                ['product_id' => $this->product->id, 'quantity' => 2],
            ]]);

        // 4. Checkout
        $checkoutResponse = $this->postJson('/api/checkout', [
            'shipping_address' => '456 E2E Blvd, Test City',
            'phone_number' => '0987654321',
            'payment_method' => 'cod',
        ]);

        $checkoutResponse->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'user_id', 'total_amount', 'status', 'items']]);

        $checkoutData = $checkoutResponse->json('data');

        $this->assertEquals($this->user->id, $checkoutData['user_id']);
        $this->assertEquals('pending', $checkoutData['status']);
        $this->assertEquals(499800, (int) $checkoutData['total_amount']);

        // 5. Cart is cleared
        $this->assertDatabaseMissing('carts', ['user_id' => $this->user->id]);

        // 6. Order exists with correct data
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total_amount' => 499800,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cod',
            'shipping_address' => '456 E2E Blvd, Test City',
            'phone_number' => '0987654321',
        ]);

        // 7. Stock correctly decremented: 50 - 2 = 48
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 48,
        ]);
    }
}
