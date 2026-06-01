<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // db seeding for categories table //
        DB::table('categories')->delete();

        $categories = ['Kitchen Appliances', 'Cookware', 'Bakeware', 'Tableware', 'Storage & Organization'];

        foreach ($categories as $index => $name) {
            DB::table('categories')->insert([
                'id'         => $index + 1,
                'name'       => $name,
                'slug'       => Str::slug($name),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}