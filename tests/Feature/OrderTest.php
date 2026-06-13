<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'd',
            'price' => 10.50,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);
    }

    private function addToCart(int $quantity): void
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => $quantity,
        ]);
    }

    private function validPayload(): array
    {
        return [
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ];
    }

    public function test_guest_cannot_access_orders(): void
    {
        $this->getJson('/api/orders')->assertStatus(401);
        $this->getJson('/api/orders/1')->assertStatus(401);
        $this->postJson('/api/orders', [])->assertStatus(401);
    }

    public function test_cannot_create_order_with_empty_cart(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/orders', $this->validPayload());

        $response->assertStatus(422)
            ->assertJson(['message' => 'Cart is empty.']);
    }

    public function test_can_create_order_successfully(): void
    {
        $this->addToCart(2);

        $response = $this->actingAs($this->user)->postJson('/api/orders', $this->validPayload());

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_amount',
                    'items' => [
                        ['id', 'product_id', 'quantity', 'price'],
                    ],
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total_amount' => 21.00,
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 10.50,
        ]);
    }

    public function test_stock_is_reduced_after_order(): void
    {
        $this->addToCart(2);

        $this->actingAs($this->user)->postJson('/api/orders', $this->validPayload());

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 3,
        ]);
    }

    public function test_cart_is_cleared_after_order(): void
    {
        $this->addToCart(2);

        $this->actingAs($this->user)->postJson('/api/orders', $this->validPayload());

        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
        ]);
    }

    public function test_rollback_when_stock_insufficient(): void
    {
        $this->addToCart(10);

        $response = $this->actingAs($this->user)->postJson('/api/orders', $this->validPayload());

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', ['user_id' => $this->user->id]);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 5]);
        $this->assertDatabaseHas('carts', ['user_id' => $this->user->id, 'quantity' => 10]);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $otherUser = User::factory()->create();
        $order = Order::create([
            'user_id' => $otherUser->id,
            'total_amount' => 10.00,
            'shipping_address' => '123',
            'phone_number' => '456',
        ]);

        $this->actingAs($this->user)->getJson("/api/orders/{$order->id}")
            ->assertStatus(403);
    }

    public function test_user_can_view_own_order(): void
    {
        $this->addToCart(2);
        $response = $this->actingAs($this->user)->postJson('/api/orders', $this->validPayload());
        $orderId = $response->json('data.id');

        $this->actingAs($this->user)->getJson("/api/orders/{$orderId}")
            ->assertStatus(200)
            ->assertJson(['data' => ['id' => $orderId]]);
    }
}
