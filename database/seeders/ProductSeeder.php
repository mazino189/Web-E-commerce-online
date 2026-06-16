<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        $this->seedKitchenProducts();

        $apiProductsSeeded = $this->seedElectronicsFromDummyJson();

        if ($apiProductsSeeded === 0) {
            Log::warning('API seeding returned 0 products due to network timeout or error. Falling back to offline electronics dataset.');
            $this->seedOfflineElectronics();
        }
    }

    private function seedKitchenProducts(): void
    {
        $kitchenProducts = [
            [
                'name' => 'Stoneware Dinner Plate Set',
                'slug' => 'stoneware-dinner-plate-set',
                'description' => 'Complete 10-piece stoneware dinner plate set.',
                'price' => 2285000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600',
                'category_slug' => 'tableware',
                'brand_name' => 'Le Creuset'
            ],
            [
                'name' => 'Heritage Serving Bowl Set',
                'slug' => 'heritage-serving-bowl-set',
                'description' => 'Premium ceramic serving bowls.',
                'price' => 2793000,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600',
                'category_slug' => 'tableware',
                'brand_name' => 'Le Creuset'
            ],
            [
                'name' => 'Stoneware Cereal Bowls',
                'slug' => 'stoneware-cereal-bowls',
                'description' => 'Durable stoneware cereal bowls.',
                'price' => 2031000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600',
                'category_slug' => 'tableware',
                'brand_name' => 'Le Creuset'
            ],
            [
                'name' => 'Stoneware Salad Plates',
                'slug' => 'stoneware-salad-plates',
                'description' => 'Elegant salad plates for dining.',
                'price' => 1904000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600',
                'category_slug' => 'tableware',
                'brand_name' => 'Le Creuset'
            ],
            [
                'name' => 'Glass Food Storage Set',
                'slug' => 'glass-food-storage-set',
                'description' => 'Airtight glass food containers.',
                'price' => 1269000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1594911774802-8822a707caff?w=600',
                'category_slug' => 'food-storage',
                'brand_name' => 'Pyrex'
            ],
            [
                'name' => 'Snack & Dip Storage Containers',
                'slug' => 'snack-dip-storage-containers',
                'description' => 'Small glass containers with lids.',
                'price' => 634000,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1594911774802-8822a707caff?w=600',
                'category_slug' => 'food-storage',
                'brand_name' => 'Pyrex'
            ],
            [
                'name' => 'Simply Store Glass Set',
                'slug' => 'simply-store-glass-set',
                'description' => 'Classic glass storage containers.',
                'price' => 888000,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1594911774802-8822a707caff?w=600',
                'category_slug' => 'food-storage',
                'brand_name' => 'Pyrex'
            ],
            [
                'name' => 'Easy Grab Glass Containers',
                'slug' => 'easy-grab-glass-containers',
                'description' => 'Convenient glass storage with easy-carry handles.',
                'price' => 1142000,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1594911774802-8822a707caff?w=600',
                'category_slug' => 'food-storage',
                'brand_name' => 'Pyrex'
            ]
        ];

        foreach ($kitchenProducts as $item) {
            $category = Category::where('slug', $item['category_slug'])->first();
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($item['brand_name'])],
                ['name' => $item['brand_name'], 'status' => true]
            );

            if ($category && $brand) {
                Product::firstOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                        'image' => $item['image'],
                        'status' => true,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );
            }
        }
    }

    private function seedElectronicsFromDummyJson(): int
    {
        $apiCategories = [
            'laptops' => 'laptops-computers',
            'smartphones' => 'smartphones-tablets',
            'mobile-accessories' => 'accessories'
        ];

        $seededCount = 0;

        foreach ($apiCategories as $dummyCat => $localSlug) {
            $category = Category::where('slug', $localSlug)->first();
            if (!$category) continue;

            $url = "https://dummyjson.com/products/category/{$dummyCat}";

            try {
                $response = Http::timeout(6)->retry(2, 100)->get($url);

                if ($response->successful()) {
                    $products = $response->json()['products'] ?? [];

                    foreach ($products as $item) {
                        $brandName = $item['brand'] ?? 'Generic';
                        $brandSlug = Str::slug($brandName);

                        $brand = Brand::firstOrCreate(
                            ['slug' => $brandSlug],
                            ['name' => $brandName, 'status' => true]
                        );

                        Product::firstOrCreate(
                            ['slug' => Str::slug($item['title'])],
                            [
                                'name' => $item['title'],
                                'description' => $item['description'] ?? 'No description available.',
                                'price' => ($item['price'] ?? 100) * 25400,
                                'stock' => $item['stock'] ?? 10,
                                'image' => $item['thumbnail'] ?? 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=600',
                                'status' => true,
                                'category_id' => $category->id,
                                'brand_id' => $brand->id,
                            ]
                        );
                        $seededCount++;
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Failed to fetch from DummyJSON for category {$dummyCat}: " . $e->getMessage());
            }
        }

        return $seededCount;
    }

    private function seedOfflineElectronics(): void
    {
        $offlineProducts = [
            [
                'name' => 'Apple MacBook Pro 16',
                'slug' => 'apple-macbook-pro-16',
                'description' => 'M3 Max chip, 48GB unified memory, 1TB SSD, 16-inch Liquid Retina XDR display with 22-hour battery life for pro workflows.',
                'price' => 69999000,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Apple'
            ],
            [
                'name' => 'Dell XPS 15',
                'slug' => 'dell-xps-15',
                'description' => '13th Gen Intel Core i7, 16GB RAM, 512GB SSD, 15.6-inch OLED InfinityEdge display with stunning colour accuracy.',
                'price' => 45999000,
                'stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=600',
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Dell'
            ],
            [
                'name' => 'Samsung Galaxy Book 3 Ultra',
                'slug' => 'samsung-galaxy-book-3-ultra',
                'description' => 'Intel Core i9, NVIDIA RTX 4070, 32GB RAM, 16-inch AMOLED display with 120Hz refresh rate.',
                'price' => 53999000,
                'stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1496181130204-755241544e35?w=600',
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Samsung'
            ],
            [
                'name' => 'Apex Nova 14 Ultra',
                'slug' => 'apex-nova-14-ultra',
                'description' => 'Neural-class M-Series 32GB laptop with 14-inch OLED display and all-day battery life.',
                'price' => 32490000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600',
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Apex'
            ],
            [
                'name' => 'Apple iPhone 15 Pro Max',
                'slug' => 'apple-iphone-15-pro-max',
                'description' => 'A17 Pro chip, 48MP pro camera system, 6.7-inch Super Retina XDR display, titanium design with 5G connectivity.',
                'price' => 34999000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600',
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple'
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Snapdragon 8 Gen 3, 200MP camera with AI-powered editing, S Pen, titanium frame, and Galaxy AI features.',
                'price' => 29999000,
                'stock' => 22,
                'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600',
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung'
            ],
            [
                'name' => 'Nimbus Pulse X1',
                'slug' => 'nimbus-pulse-x1',
                'description' => 'Flagship smartphone with 5G connectivity and 200MP camera system.',
                'price' => 24999000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600',
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Nimbus'
            ],
            [
                'name' => 'Apple iPad Air M2',
                'slug' => 'apple-ipad-air-m2',
                'description' => 'M2 chip, 11-inch Liquid Retina display, 256GB storage, Apple Pencil Pro support, and all-day battery life.',
                'price' => 19999000,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600',
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple'
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 Ultra',
                'slug' => 'samsung-galaxy-tab-s9-ultra',
                'description' => '14.6-inch Dynamic AMOLED display, Snapdragon 8 Gen 2, IP68 water resistance, S Pen included.',
                'price' => 27999000,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1589739900243-4b52cd9dd8df?w=600',
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung'
            ],
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'slug' => 'sony-wh-1000xm5-headphones',
                'description' => 'Industry-leading noise cancellation, 30-hour battery, crystal-clear hands-free calling, and lightweight ergonomic design.',
                'price' => 10159000,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1505740420928-65eab1f1b6f8?w=600',
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Sony'
            ],
            [
                'name' => 'Apple AirPods Pro 2',
                'slug' => 'apple-airpods-pro-2',
                'description' => 'Adaptive audio, active noise cancellation, personalised spatial audio, USB-C charging case with Find My support.',
                'price' => 7999000,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1588423771073-b8903fbb85b5?w=600',
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Apple'
            ],
            [
                'name' => 'Bose QuietComfort Ultra',
                'slug' => 'bose-quietcomfort-ultra',
                'description' => 'Spatial audio, CustomTune noise cancellation, immersiv feel, and 24-hour battery life for premium wireless listening.',
                'price' => 10919000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1545456200-1af3e99c0b3c?w=600',
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Bose'
            ],
            [
                'name' => 'Volt Phase Pro Speaker',
                'slug' => 'volt-phase-pro-speaker',
                'description' => 'Cozy smart speaker with crystal clear 360-degree audio and multi-room support.',
                'price' => 820000,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1543510473-ac2c35329a28?w=600',
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Volt'
            ],
            [
                'name' => 'Apple Watch Ultra 2',
                'slug' => 'apple-watch-ultra-2',
                'description' => 'Titanium case, precision dual-frequency GPS, 100m water resistance, 36-hour battery, and advanced health sensors.',
                'price' => 20319000,
                'stock' => 14,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Apple'
            ],
            [
                'name' => 'Samsung Galaxy Watch 6 Pro',
                'slug' => 'samsung-galaxy-watch-6-pro',
                'description' => '47mm titanium case, sapphire crystal, body composition analysis, sleep coaching, and Wear OS with Google services.',
                'price' => 11419000,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1508681934037-5f83f9b7e1c5?w=600',
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Samsung'
            ],
            [
                'name' => 'Orbit Halo Watch',
                'slug' => 'orbit-halo-watch',
                'description' => 'Premium smartwatch with active health tracking, built-in GPS, and 14-day battery life.',
                'price' => 11999000,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600',
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Orbit'
            ],
        ];

        foreach ($offlineProducts as $item) {
            $category = Category::where('slug', $item['category_slug'])->first();
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($item['brand_name'])],
                ['name' => $item['brand_name'], 'status' => true]
            );

            if ($category && $brand) {
                Product::firstOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                        'image' => $item['image'],
                        'status' => true,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );
            }
        }
    }
}
