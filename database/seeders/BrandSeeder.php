<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'KitchenPro', 'slug' => 'kitchenpro', 'description' => 'Premium kitchen appliances and cookware', 'status' => true],
            ['name' => 'ChefSelect', 'slug' => 'chefselect', 'description' => 'Professional-grade cooking essentials', 'status' => true],
            ['name' => 'HomeCraft', 'slug' => 'homecraft', 'description' => 'Quality kitchen tools for home chefs', 'status' => true],
            ['name' => 'NatureTable', 'slug' => 'naturetable', 'description' => 'Eco-friendly tableware and serving', 'status' => true],
            ['name' => 'BakeMaster', 'slug' => 'bakemaster', 'description' => 'Everything for baking enthusiasts', 'status' => true],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }
    }
}
