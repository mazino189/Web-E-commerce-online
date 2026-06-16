<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptops & Ultrabooks', 'slug' => 'laptops', 'description' => 'High-performance laptops, ultrabooks, and tablets for work and play.'],
            ['name' => 'Smartphones', 'slug' => 'smartphones', 'description' => 'Flagship smartphones with cutting-edge technology and design.'],
            ['name' => 'Audio & Speakers', 'slug' => 'audio', 'description' => 'Premium audio players, speakers, and sound systems.'],
            ['name' => 'Wearables', 'slug' => 'wearables', 'description' => 'Smartwatches, fitness trackers, and wearable tech.'],
            ['name' => 'Peripherals', 'slug' => 'peripherals', 'description' => 'Keyboards, mice, monitors, and desktop accessories.'],
            ['name' => 'Headphones & Earphones', 'slug' => 'headphones', 'description' => 'Wireless headphones, earbuds, and audio headsets.'],
            ['name' => 'Cameras & Optics', 'slug' => 'cameras', 'description' => 'Mirrorless cameras, lenses, drones, and imaging gear.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
