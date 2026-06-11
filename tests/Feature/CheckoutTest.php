<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
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

    private function payload(string $method = 'cod'): array
    {
        return [
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
            'payment_method' => $method,
        ];
    }

    public function test_guest_cannot_checkout(): void
    {
        $this->postJson('/checkout', $this->payload())->assertStatus(401);
    }

    public function test_empty_cart_denied(): void
    {
        $response = $this->actingAs($this->user)->postJson('/checkout', $this->payload());

        $response->assertStatus(422)
            ->assertJson(['message' => 'Cart is empty.']);
    }

    public function test_checkout_with_cod(): void
    {
        $this->addToCart(2);

        $response = $this->actingAs($this->user)->postJson('/checkout', $this->payload('cod'));

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cod',
        ]);
    }

    public function test_checkout_with_bank_transfer(): void
    {
        $this->addToCart(2);

        $response = $this->actingAs($this->user)->postJson('/checkout', $this->payload('bank_transfer'));

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'bank_transfer',
        ]);
    }

    public function test_cart_cleared_after_checkout(): void
    {
        $this->addToCart(2);

        $this->actingAs($this->user)->postJson('/checkout', $this->payload());

        $this->assertDatabaseMissing('carts', ['user_id' => $this->user->id]);
    }

    public function test_stock_reduced_after_checkout(): void
    {
        $this->addToCart(2);

        $this->actingAs($this->user)->postJson('/checkout', $this->payload());

        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 3]);
    }

    public function test_order_status_is_pending(): void
    {
        $this->addToCart(1);

        $response = $this->actingAs($this->user)->postJson('/checkout', $this->payload());

        $response->assertJson(['status' => 'pending']);
    }

    public function test_insufficient_stock_rollback(): void
    {
        $this->addToCart(10);

        $response = $this->actingAs($this->user)->postJson('/checkout', $this->payload());

        $response->assertStatus(422);
        $this->assertDatabaseMissing('orders', ['user_id' => $this->user->id]);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 5]);
    }
}
