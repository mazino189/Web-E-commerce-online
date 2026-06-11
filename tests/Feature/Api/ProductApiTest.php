<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create(['name' => 'Kitchen', 'slug' => 'kitchen']);
        $this->brand = Brand::create(['name' => 'Philips', 'slug' => 'philips']);
    }

    public function test_can_list_products(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Blender',
            'slug' => 'blender',
            'description' => 'A blender',
            'price' => 49.99,
            'image' => 'b.jpg',
            'stock' => 10,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta', 'links']);
    }

    public function test_can_show_product(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Blender',
            'slug' => 'blender',
            'description' => 'A blender',
            'price' => 49.99,
            'image' => 'b.jpg',
            'stock' => 10,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => 'Blender',
                ],
            ]);
    }

    public function test_can_search_products_by_name(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Coffee Maker',
            'slug' => 'coffee-maker',
            'description' => 'Makes coffee',
            'price' => 79.99,
            'image' => 'c.jpg',
            'stock' => 5,
        ]);

        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Toaster',
            'slug' => 'toaster',
            'description' => 'Toasts bread',
            'price' => 29.99,
            'image' => 't.jpg',
            'stock' => 8,
        ]);

        $response = $this->getJson('/api/products?search=Coffee');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Coffee Maker', $response->json('data.0.name'));
    }

    public function test_can_filter_by_category(): void
    {
        $cat2 = Category::create(['name' => 'Bakeware', 'slug' => 'bakeware']);

        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Blender',
            'slug' => 'blender',
            'description' => 'd',
            'price' => 49.99,
            'image' => 'b.jpg',
            'stock' => 10,
        ]);

        Product::create([
            'category_id' => $cat2->id,
            'brand_id' => $this->brand->id,
            'name' => 'Oven',
            'slug' => 'oven',
            'description' => 'd',
            'price' => 199.99,
            'image' => 'o.jpg',
            'stock' => 3,
        ]);

        $response = $this->getJson('/api/products?category_id=' . $this->category->id);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Blender', $response->json('data.0.name'));
    }

    public function test_can_filter_by_brand(): void
    {
        $brand2 = Brand::create(['name' => 'Tefal', 'slug' => 'tefal']);

        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Blender',
            'slug' => 'blender',
            'description' => 'd',
            'price' => 49.99,
            'image' => 'b.jpg',
            'stock' => 10,
        ]);

        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $brand2->id,
            'name' => 'Pan',
            'slug' => 'pan',
            'description' => 'd',
            'price' => 29.99,
            'image' => 'p.jpg',
            'stock' => 15,
        ]);

        $response = $this->getJson('/api/products?brand_id=' . $brand2->id);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Pan', $response->json('data.0.name'));
    }

    public function test_can_filter_by_price_range(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Blender',
            'slug' => 'blender',
            'description' => 'd',
            'price' => 30.00,
            'image' => 'b.jpg',
            'stock' => 10,
        ]);

        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Mixer',
            'slug' => 'mixer',
            'description' => 'd',
            'price' => 80.00,
            'image' => 'm.jpg',
            'stock' => 5,
        ]);

        Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Toaster',
            'slug' => 'toaster',
            'description' => 'd',
            'price' => 150.00,
            'image' => 't.jpg',
            'stock' => 8,
        ]);

        $response = $this->getJson('/api/products?min_price=50&max_price=100');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Mixer', $response->json('data.0.name'));
    }

    public function test_products_are_paginated(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Product::create([
                'category_id' => $this->category->id,
                'brand_id' => $this->brand->id,
                'name' => "Product {$i}",
                'slug' => "product-{$i}",
                'description' => 'd',
                'price' => 10,
                'image' => 'p.jpg',
                'stock' => 5,
            ]);
        }

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $this->assertCount(12, $response->json('data'));
        $this->assertEquals(2, $response->json('meta.last_page'));
    }

    public function test_product_includes_category_and_brand(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Blender',
            'slug' => 'blender',
            'description' => 'd',
            'price' => 49.99,
            'image' => 'b.jpg',
            'stock' => 10,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'category' => [
                        'id' => $this->category->id,
                        'name' => 'Kitchen',
                    ],
                    'brand' => [
                        'id' => $this->brand->id,
                        'name' => 'Philips',
                    ],
                ],
            ]);
    }
}
