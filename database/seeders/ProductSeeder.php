<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // path to the CSV file containing product data
        $file = database_path('imports/products.csv');

        // Check if file exists
        if (!file_exists($file)) {
            $this->command->error("File not found: " . $file);
            return;
        }

        // Read the file //
        $data = array_map('str_getcsv', file($file));

        // Remove header row
        $header = array_shift($data);

        // Undefined variable
        $processedSlugs = [];

        // Loop insert data
        foreach ($data as $row) {
            
            // fix validation for required fields (name and price) to avoid inserting empty products
            if (empty($row[0]) || empty($row[3])) {
                continue;
            }

            // Create slug from name
            $baseSlug = !empty($row[1]) ? $row[1] : Str::slug($row[0]);
            $slug = $baseSlug;

            // Control solution for duplicate slug
            while (in_array($slug, $processedSlugs)) {
                $slug = $baseSlug . '-' . Str::lower(Str::random(5));
            }
            $processedSlugs[] = $slug;

            // insert or update product data using name as unique key to prevent duplicates
            DB::table('products')->updateOrInsert(
                ['name' => $row[0]], // unique key to prevent duplicates
                [
                    'slug'        => $slug,
                    'description' => $row[2] ?? 'No description',
                    'price'       => isset($row[3]) ? (int)$row[3] : 0,
                    'stock'       => isset($row[4]) ? (int)$row[4] : rand(10, 100),
                    'image'       => 'default-product.jpg', // default image placeholder
                    'category_id' => isset($row[5]) ? (int)$row[5] : 1,
                    'brand_id'    => isset($row[6]) ? (int)$row[6] : 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );

            $this->command->info("Product Imported: " . $row[0]);
        } // close foreach
    } // close run
} // close class