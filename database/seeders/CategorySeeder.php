<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kitchen Appliances', 'slug' => 'kitchen-appliances', 'description' => 'Essential appliances for your kitchen', 'status' => true],
            ['name' => 'Cookware', 'slug' => 'cookware', 'description' => 'Pots, pans, and cooking essentials', 'status' => true],
            ['name' => 'Tableware', 'slug' => 'tableware', 'description' => 'Plates, bowls, and dining essentials', 'status' => true],
            ['name' => 'Bakeware', 'slug' => 'bakeware', 'description' => 'Baking trays, pans, and accessories', 'status' => true],
            ['name' => 'Kitchen Tools', 'slug' => 'kitchen-tools', 'description' => 'Utensils, gadgets, and handy tools', 'status' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
