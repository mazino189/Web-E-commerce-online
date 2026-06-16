<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'KitchenAid', 'slug' => 'kitchenaid', 'description' => 'Premium kitchen appliances and stand mixer icon.'],
            ['name' => 'Cuisinart', 'slug' => 'cuisinart', 'description' => 'Versatile cookware and countertop appliance leader.'],
            ['name' => 'Le Creuset', 'slug' => 'le-creuset', 'description' => 'French enameled cast iron and stoneware craftsmanship.'],
            ['name' => 'Lodge', 'slug' => 'lodge', 'description' => 'American cast iron cookware since 1896.'],
            ['name' => 'OXO', 'slug' => 'oxo', 'description' => 'Ergonomic kitchen tools and bakeware.'],
            ['name' => 'Pyrex', 'slug' => 'pyrex', 'description' => 'Tempered glass bakeware and food storage.'],
            ['name' => 'Staub', 'slug' => 'staub', 'description' => 'French enameled cast iron cocottes and cookware.'],
            ['name' => 'Zwilling', 'slug' => 'zwilling', 'description' => 'German precision knives and kitchen cutlery.'],
            ['name' => 'Apple', 'slug' => 'apple', 'description' => 'Innovative smartphones, laptops, and wearable technology.'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'description' => 'Leading electronics, smartphones, displays, and home appliances.'],
            ['name' => 'Sony', 'slug' => 'sony', 'description' => 'Premium audio, cameras, and consumer electronics.'],
            ['name' => 'Dell', 'slug' => 'dell', 'description' => 'Business and personal computing solutions.'],
            ['name' => 'Anker', 'slug' => 'anker', 'description' => 'Charging technology, cables, and mobile accessories.'],
            ['name' => 'Logitech', 'slug' => 'logitech', 'description' => 'Computer peripherals, mice, keyboards, and gaming gear.'],
            ['name' => 'Keychron', 'slug' => 'keychron', 'description' => 'Mechanical keyboards for Mac, Windows, and mobile.'],
            ['name' => 'Bose', 'slug' => 'bose', 'description' => 'High-fidelity audio speakers and noise-cancelling headphones.'],
            ['name' => 'Canon', 'slug' => 'canon', 'description' => 'Professional cameras, lenses, and imaging solutions.'],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'description' => 'ThinkPad laptops, workstations, and enterprise hardware.'],
            ['name' => 'HP', 'slug' => 'hp', 'description' => 'Laptops, printers, and computing solutions.'],
            ['name' => 'ASUS', 'slug' => 'asus', 'description' => 'ROG gaming, ZenBook laptops, and PC components.'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'description' => 'Smartphones, smart home, and ecosystem devices.'],
            ['name' => 'Google', 'slug' => 'google', 'description' => 'Pixel phones, Nest, and wearable technology.'],
            ['name' => 'JBL', 'slug' => 'jbl', 'description' => 'Portable speakers, headphones, and pro audio.'],
            ['name' => 'Apex', 'slug' => 'apex', 'description' => 'Premium computing and display solutions.'],
            ['name' => 'Nimbus', 'slug' => 'nimbus', 'description' => 'Next-gen mobile and audio technology.'],
            ['name' => 'Volt', 'slug' => 'volt', 'description' => 'High-fidelity audio and peripheral engineering.'],
            ['name' => 'Orbit', 'slug' => 'orbit', 'description' => 'Innovative wearables and smart accessories.'],
            ['name' => 'Lumen', 'slug' => 'lumen', 'description' => 'Professional imaging and optical systems.'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }
    }
}
