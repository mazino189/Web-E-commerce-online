<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_brands(): void
    {
        Brand::create(['name' => 'Philips', 'slug' => 'philips']);
        Brand::create(['name' => 'Tefal', 'slug' => 'tefal']);

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_show_brand(): void
    {
        $brand = Brand::create(['name' => 'Panasonic', 'slug' => 'panasonic']);

        $response = $this->getJson("/api/brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $brand->id,
                    'name' => 'Panasonic',
                    'slug' => 'panasonic',
                ],
            ]);
    }
}
