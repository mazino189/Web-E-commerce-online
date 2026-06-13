<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $brands = Brand::pluck('id', 'slug');

        $products = [
            [
                'name' => 'Professional Blender 1200W',
                'slug' => 'professional-blender-1200w',
                'description' => 'High-performance blender with 1200W motor, 6 stainless steel blades, and 1.5L BPA-free jug. Perfect for smoothies, soups, and crushing ice.',
                'price' => 245000,
                'stock' => 50,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['kitchen-appliances'] ?? 1,
                'brand_id' => $brands['kitchenpro'] ?? 1,
            ],
            [
                'name' => 'Stainless Steel Saucepan Set (3-Piece)',
                'slug' => 'stainless-steel-saucepan-set-3-piece',
                'description' => 'Tri-ply stainless steel saucepans with tempered glass lids. Includes 1L, 2L, and 3L sizes. Oven safe up to 260°C. Suitable for all hob types.',
                'price' => 185000,
                'stock' => 35,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['cookware'] ?? 2,
                'brand_id' => $brands['chefselect'] ?? 2,
            ],
            [
                'name' => 'Porcelain Dinner Set (16-Piece)',
                'slug' => 'porcelain-dinner-set-16-piece',
                'description' => 'Elegant white porcelain dinner set serving 4. Includes 4 dinner plates, 4 side plates, 4 bowls, and 4 mugs. Dishwasher and microwave safe.',
                'price' => 320000,
                'stock' => 25,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['tableware'] ?? 3,
                'brand_id' => $brands['naturetable'] ?? 4,
            ],
            [
                'name' => 'Non-Stick Frying Pan 28cm',
                'slug' => 'non-stick-frying-pan-28cm',
                'description' => 'Professional non-stick frying pan with reinforced titanium coating. Ergonomic soft-touch handle. Suitable for gas, electric, and ceramic hobs.',
                'price' => 155000,
                'stock' => 60,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['cookware'] ?? 2,
                'brand_id' => $brands['homecraft'] ?? 3,
            ],
            [
                'name' => 'Electric Kettle 1.7L Stainless Steel',
                'slug' => 'electric-kettle-1.7l-stainless-steel',
                'description' => 'Fast-boil 2200W electric kettle with brushed stainless steel finish. Auto shut-off, boil-dry protection, and 360° swivel base with cordless serving.',
                'price' => 125000,
                'stock' => 80,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['kitchen-appliances'] ?? 1,
                'brand_id' => $brands['kitchenpro'] ?? 1,
            ],
            [
                'name' => 'Silicone Baking Mat Set (2-Pack)',
                'slug' => 'silicone-baking-mat-set-2-pack',
                'description' => 'Non-stick, reusable silicone baking mats. Perfect for roasting, baking, and rolling dough. Heat resistant up to 230°C. Dishwasher safe.',
                'price' => 45000,
                'stock' => 45,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['bakeware'] ?? 4,
                'brand_id' => $brands['bakemaster'] ?? 5,
            ],
            [
                'name' => 'Stainless Steel Mixing Bowl Set (5-Piece)',
                'slug' => 'stainless-steel-mixing-bowl-set-5-piece',
                'description' => 'Nesting mixing bowls from 1L to 5L. Deep-drawn stainless steel with non-slip silicone bases. Includes lids for food storage.',
                'price' => 95000,
                'stock' => 40,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['kitchen-tools'] ?? 5,
                'brand_id' => $brands['chefselect'] ?? 2,
            ],
            [
                'name' => 'Digital Kitchen Scale 5kg',
                'slug' => 'digital-kitchen-scale-5kg',
                'description' => 'Precision digital kitchen scale with 1g increments. Stainless steel platform, tare function, auto shut-off. Measures in g, kg, lb, oz, ml.',
                'price' => 65000,
                'stock' => 55,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['kitchen-tools'] ?? 5,
                'brand_id' => $brands['homecraft'] ?? 3,
            ],
            [
                'name' => 'Ceramic Baking Dish 33x23cm',
                'slug' => 'ceramic-baking-dish-33x23cm',
                'description' => 'Premium ceramic baking dish perfect for lasagnas, casseroles, and roasting. Oven safe to 260°C. Attractive enough for table serving.',
                'price' => 115000,
                'stock' => 30,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['bakeware'] ?? 4,
                'brand_id' => $brands['bakemaster'] ?? 5,
            ],
            [
                'name' => 'Glass Food Storage Set (12-Piece)',
                'slug' => 'glass-food-storage-set-12-piece',
                'description' => 'Borosilicate glass containers with airtight BPA-free lids. Oven, microwave, freezer, and dishwasher safe. Includes 6 rectangular and 6 round containers.',
                'price' => 175000,
                'stock' => 38,
                'image' => 'default-product.jpg',
                'status' => true,
                'category_id' => $categories['kitchen-tools'] ?? 5,
                'brand_id' => $brands['naturetable'] ?? 4,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
