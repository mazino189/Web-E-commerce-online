<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();

        $categoryMap = [
            'smartphones' => 'smartphones',
            'laptops' => 'laptops',
            'mobile-accessories' => 'mobile-accessories',
            'audio' => 'audio',
            'cameras' => 'cameras',
            'wearables' => 'wearables',
        ];

        $categoryNames = [
            'smartphones' => 'Smartphones',
            'laptops' => 'Laptops & Ultrabooks',
            'mobile-accessories' => 'Mobile Accessories',
            'audio' => 'Audio & Speakers',
            'cameras' => 'Cameras & Optics',
            'wearables' => 'Wearables',
        ];

        foreach ($categoryMap as $slug) {
            Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $categoryNames[$slug] ?? ucfirst($slug),
                    'description' => '',
                    'status' => true,
                ]
            );
        }

        $categoryIds = Category::pluck('id', 'slug');

        $dummyCategories = ['smartphones', 'laptops', 'mobile-accessories', 'audio', 'cameras', 'wearables'];

        foreach ($dummyCategories as $cat) {
            $response = Http::get("https://dummyjson.com/products/category/{$cat}");

            if (! $response->successful()) {
                continue;
            }

            $data = $response->json();
            $products = $data['products'] ?? [];

            foreach ($products as $item) {
                $title = $item['title'] ?? 'Unknown';
                $slug = Str::slug($title);
                $price = ($item['price'] ?? 0) * 25400;
                $stock = $item['stock'] ?? rand(1, 50);

                $brandName = $item['brand'] ?? 'Generic';
                $brandSlug = Str::slug($brandName);

                $brand = Brand::firstOrCreate(
                    ['slug' => $brandSlug],
                    [
                        'name' => $brandName,
                        'description' => '',
                        'status' => true,
                    ]
                );

                $ourCategorySlug = $categoryMap[$item['category']] ?? $item['category'];
                $categoryId = $categoryIds[$ourCategorySlug] ?? null;

                if (! $categoryId) {
                    continue;
                }

                Product::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $title,
                        'description' => $item['description'] ?? '',
                        'price' => $price,
                        'stock' => $stock,
                        'image' => $item['thumbnail'] ?? '',
                        'status' => true,
                        'category_id' => $categoryId,
                        'brand_id' => $brand->id,
                    ]
                );
            }
        }
    }
}
