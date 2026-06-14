<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'KitchenAid', 'slug' => 'kitchenaid', 'description' => 'Premium kitchen appliances and stand mixers.'],
            ['name' => 'Cuisinart', 'slug' => 'cuisinart', 'description' => 'Innovative cookware and electric appliances.'],
            ['name' => 'Le Creuset', 'slug' => 'le-creuset', 'description' => 'Iconic enameled cast iron and stoneware.'],
            ['name' => 'Lodge', 'slug' => 'lodge', 'description' => 'Cast iron cookware since 1896.'],
            ['name' => 'OXO', 'slug' => 'oxo', 'description' => 'Smart kitchen tools and bakeware.'],
            ['name' => 'Pyrex', 'slug' => 'pyrex', 'description' => 'Tempered glass bakeware and food storage.'],
            ['name' => 'Staub', 'slug' => 'staub', 'description' => 'French enameled cast iron cookware.'],
            ['name' => 'Zwilling', 'slug' => 'zwilling', 'description' => 'German-engineered knives and kitchen tools.'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }
    }
}
