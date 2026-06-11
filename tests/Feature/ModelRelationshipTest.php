<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_has_many_products(): void
    {
        $brand = Brand::create([
            'name' => 'Test Brand',
            'slug' => 'test-brand',
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'price' => 99.99,
            'image' => 'test.jpg',
            'stock' => 10,
        ]);

        $this->assertCount(1, $brand->products);
        $this->assertTrue($brand->products->contains($product));
        $this->assertInstanceOf(Product::class, $brand->products->first());
    }

    public function test_category_has_many_products(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $brand = Brand::create([
            'name' => 'Test Brand',
            'slug' => 'test-brand',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'price' => 99.99,
            'image' => 'test.jpg',
            'stock' => 10,
        ]);

        $this->assertCount(1, $category->products);
        $this->assertTrue($category->products->contains($product));
        $this->assertInstanceOf(Product::class, $category->products->first());
    }

    public function test_cart_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertEquals($user->id, $cart->user->id);
    }

    public function test_cart_belongs_to_product(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertInstanceOf(Product::class, $cart->product);
        $this->assertEquals($product->id, $cart->product->id);
    }

    public function test_order_belongs_to_user(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 199.99,
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    public function test_order_has_many_items(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 20.00,
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 10.00,
        ]);

        $this->assertCount(1, $order->items);
        $this->assertTrue($order->items->contains($item));
        $this->assertInstanceOf(OrderItem::class, $order->items->first());
    }

    public function test_order_item_belongs_to_order(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 10.00,
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ]);

        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 10.00,
        ]);

        $this->assertInstanceOf(Order::class, $item->order);
        $this->assertEquals($order->id, $item->order->id);
    }

    public function test_order_item_belongs_to_product(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 10.00,
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ]);

        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 10.00,
        ]);

        $this->assertInstanceOf(Product::class, $item->product);
        $this->assertEquals($product->id, $item->product->id);
    }

    public function test_product_belongs_to_category(): void
    {
        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_product_belongs_to_brand(): void
    {
        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertEquals($brand->id, $product->brand->id);
    }

    public function test_user_has_many_carts(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'C', 'slug' => 'c']);
        $brand = Brand::create(['name' => 'B', 'slug' => 'b']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'P',
            'slug' => 'p',
            'description' => 'd',
            'price' => 10,
            'image' => 'i.jpg',
            'stock' => 5,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->assertCount(1, $user->carts);
        $this->assertTrue($user->carts->contains($cart));
        $this->assertInstanceOf(Cart::class, $user->carts->first());
    }

    public function test_user_has_many_orders(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 50.00,
            'shipping_address' => '123 Test St',
            'phone_number' => '1234567890',
        ]);

        $this->assertCount(1, $user->orders);
        $this->assertTrue($user->orders->contains($order));
        $this->assertInstanceOf(Order::class, $user->orders->first());
    }
}
