<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kitchen Appliances', 'slug' => 'kitchen-appliances', 'description' => 'Essential countertop appliances and tools for everyday cooking.'],
            ['name' => 'Cookware', 'slug' => 'cookware', 'description' => 'Pots, pans, skillets, and roasting vessels for every recipe.'],
            ['name' => 'Tableware', 'slug' => 'tableware', 'description' => 'Plates, glasses, bowls, and serving pieces for dining.'],
            ['name' => 'Baking Tools', 'slug' => 'baking-tools', 'description' => 'Baking sheets, mixing bowls, measuring cups, and bakeware.'],
            ['name' => 'Food Storage', 'slug' => 'food-storage', 'description' => 'Containers, jars, and organisers for fresh ingredient storage.'],
            ['name' => 'Laptops & Computers', 'slug' => 'laptops-computers', 'description' => 'High-performance laptops, ultrabooks, and desktop computers.'],
            ['name' => 'Smartphones & Tablets', 'slug' => 'smartphones-tablets', 'description' => 'Flagship smartphones, tablets, and mobile devices.'],
            ['name' => 'Audio & Speakers', 'slug' => 'audio-speakers', 'description' => 'Premium headphones, speakers, and audio systems.'],
            ['name' => 'Wearables & Smartwatches', 'slug' => 'wearables-smartwatches', 'description' => 'Smartwatches, fitness trackers, and wearable technology.'],
            ['name' => 'Cameras & Photography', 'slug' => 'cameras-photography', 'description' => 'Mirrorless cameras, lenses, drones, and imaging gear.'],
            ['name' => 'Gaming Gear', 'slug' => 'gaming-gear', 'description' => 'Console, PC gaming accessories, controllers, and peripherals.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Cables, chargers, cases, and mobile accessories.'],
            ['name' => 'Peripherals', 'slug' => 'peripherals', 'description' => 'Keyboards, mice, monitors, and desktop peripherals.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
