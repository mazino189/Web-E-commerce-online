<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
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
