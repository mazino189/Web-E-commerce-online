<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kitchen Appliances', 'slug' => 'kitchen-appliances', 'description' => 'Blenders, kettles, and other electric appliances.'],
            ['name' => 'Cookware', 'slug' => 'cookware', 'description' => 'Pots, pans, and high-quality skillet sets.'],
            ['name' => 'Tableware', 'slug' => 'tableware', 'description' => 'Dinner sets, elegant plates, and serving bowls.'],
            ['name' => 'Baking Tools', 'slug' => 'baking-tools', 'description' => 'Baking mats, trays, tins, and accessories.'],
            ['name' => 'Food Storage', 'slug' => 'food-storage', 'description' => 'Airtight food containers and organizing jars.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
