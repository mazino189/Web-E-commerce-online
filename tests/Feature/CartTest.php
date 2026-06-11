<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
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
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);
    }

    public function test_guest_cannot_access_cart(): void
    {
        $this->getJson('/cart')->assertStatus(401);
        $this->postJson('/cart', ['product_id' => 1, 'quantity' => 1])->assertStatus(401);
        $this->putJson('/cart/1', ['quantity' => 1])->assertStatus(401);
        $this->deleteJson('/cart/1')->assertStatus(401);
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $response = $this->actingAs($this->user)->postJson('/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    public function test_adding_same_product_increases_quantity(): void
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->postJson('/cart', [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 5,
        ]);
    }

    public function test_cannot_add_product_exceeding_stock(): void
    {
        $response = $this->actingAs($this->user)->postJson('/cart', [
            'product_id' => $this->product->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Insufficient stock. Only 5 available.']);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_cannot_update_exceeding_stock(): void
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->putJson("/cart/{$cart->id}", [
            'quantity' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Insufficient stock. Only 5 available.']);
    }

    public function test_user_can_update_cart_quantity(): void
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->putJson("/cart/{$cart->id}", [
            'quantity' => 4,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 4,
        ]);
    }

    public function test_user_can_remove_cart_item(): void
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/cart/{$cart->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_user_cannot_modify_another_users_cart(): void
    {
        $otherUser = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $otherUser->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($this->user)->putJson("/cart/{$cart->id}", ['quantity' => 3])
            ->assertStatus(403);

        $this->actingAs($this->user)->deleteJson("/cart/{$cart->id}")
            ->assertStatus(403);
    }
}
