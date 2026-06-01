<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->delete();

        $brands = ['Philips', 'Panasonic', 'Tefal', 'Lock&Lock', 'Sunhouse'];

        foreach ($brands as $index => $name) {
            DB::table('brands')->insert([
                'id'         => $index + 1,
                'name'       => $name,
                'slug'       => Str::slug($name),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}