<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::create(['name' => 'Kitchen', 'slug' => 'kitchen']);
        Category::create(['name' => 'Bakeware', 'slug' => 'bakeware']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_show_category(): void
    {
        $category = Category::create(['name' => 'Cookware', 'slug' => 'cookware']);

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => 'Cookware',
                    'slug' => 'cookware',
                ],
            ]);
    }
}
