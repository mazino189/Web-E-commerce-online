<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        $this->seedKitchenProducts();
        $this->seedElectronicsFromDummyJson();
    }

    private function seedKitchenProducts(): void
    {
        $category = fn (string $slug) => Category::whereSlug($slug)->value('id');
        $brand = fn (string $slug) => Brand::whereSlug($slug)->value('id');

        $photos = [
            '1556909114-f6e7ad7d3136', '1610701596007-11502861dc1c',
            '1563865436874-9aef320d4c90', '1594226801341-41427b4e8e4c',
            '1590794056226-04ef0b1a4b6b', '1577805947697-89e18249d767',
            '1547592166-23ac45744aec', '1584990347449-b41d0f3e1f0b',
            '1579111757168-1ef0e10a8e1e', '1560343090-741c6e1b0f8b',
            '1600585154340-be6161a56a0c', '1606913919536-0158f7f7b3e1',
            '1507003211668-5e2e7e6e3f5a', '1556909114-f6e7ad7d3136',
            '1615360582858-04b1d0b1e5e1', '1579111757168-1ef0e10a8e1e',
            '1560343090-741c6e1b0f8b', '1610701596007-11502861dc1c',
            '1547592166-23ac45744aec', '1584990347449-b41d0f3e1f0b',
        ];

        $img = fn (int $i) => "https://images.unsplash.com/photo-{$photos[$i]}?w=400&h=400&fit=crop&q=80";

        $kitchen = [
            // kitchen-appliances (4)
            [0,  'kitchenaid',     'kitchen-appliances', 'Professional Stand Mixer',     'professional-stand-mixer',     'Powerful 5-quart stand mixer with tilt-head design, 10 speeds, and planetary mixing action for perfect dough, batter, and whipped cream.',                 499.99, 15],
            [1,  'kitchenaid',     'kitchen-appliances', 'Artisan Blender',              'artisan-blender',              'High-performance blender with 1.5-litre pitcher, 7-speed control, and self-cleaning cycle for smoothies, soups, and frozen drinks.',                      249.99, 20],
            [2,  'cuisinart',      'kitchen-appliances', '14-Cup Food Processor',        '14-cup-food-processor',        'Extra-large 14-cup food processor with 720-watt motor, S-blade, slicing and shredding discs, and a dough blade for all your prep needs.',                  179.99, 12],
            [3,  'oxo',            'kitchen-appliances', 'Mandoline Slicer',             'mandoline-slicer',             'Adjustable mandoline slicer with four interchangeable blades, ergonomic handle, and a safety food holder for precision slicing.',                         69.99,  25],
            // cookware (5)
            [4,  'cuisinart',      'cookware',           'Tri-Ply Stainless Cookware Set','tri-ply-stainless-cookware-set','10-piece tri-ply stainless steel cookware set with aluminum core, tempered glass lids, and riveted handles for even heat distribution.',                 399.99, 8],
            [5,  'lodge',          'cookware',           'Cast Iron Skillet',            'cast-iron-skillet',            'Pre-seasoned 12-inch cast iron skillet with superior heat retention, naturally non-stick surface, and oven-safe up to 500°F for searing and baking.',         44.99,  30],
            [6,  'staub',          'cookware',           'Round Cocotte Dutch Oven',      'round-cocotte-dutch-oven',     '5.5-quart enameled cast iron dutch oven with tight-fitting lid, self-basting spikes, and a smooth enamel interior that resists sticking.',                349.99, 10],
            [7,  'cuisinart',      'cookware',           'Stainless Saucepan Set',       'stainless-saucepan-set',       '3-piece stainless steel saucepan set (1.5, 2.5, 3.5-quart) with encapsulated base, cool-grip handles, and pour spouts for mess-free serving.',              129.99, 18],
            [8,  'staub',          'cookware',           'Non-Stick Fry Pan',            'non-stick-fry-pan',            '11-inch non-stick fry pan with PFOA-free coating, magnetic induction base, and ergonomic riveted stainless steel handle.',                                89.99,  22],
            // tableware (3)
            [9,  'le-creuset',     'tableware',          'Stoneware Dinner Plate Set',   'stoneware-dinner-plate-set',   'Set of 4 stoneware dinner plates (10.75-inch) with hand-applied glaze, chip-resistant edge, and microwave, oven, and dishwasher safe construction.',         89.99,  20],
            [10, 'zwilling',       'tableware',          'Senso Wine Glasses Set',       'senso-wine-glasses-set',       'Set of 4 crystal wine glasses (550ml) with fine-rim design, lead-free crystal, and dishwasher-safe durability for everyday elegance.',                     59.99,  15],
            [11, 'le-creuset',     'tableware',          'Heritage Serving Bowl Set',    'heritage-serving-bowl-set',    'Set of 3 stoneware serving bowls (1.5, 2.5, 4-quart) with vibrant glaze, scalloped edges, and superior heat retention for family meals.',                 109.99, 12],
            // baking-tools (5)
            [12, 'oxo',            'baking-tools',       'Non-Stick Baking Sheet Set',   'non-stick-baking-sheet-set',   'Set of 3 non-stick baking sheets (full, half, quarter-sheet) with rolled edges, warp-resistant steel, and food-grade silicone coating.',                    44.99,  28],
            [13, 'le-creuset',     'baking-tools',       'Mixing Bowl Set',              'mixing-bowl-set',              'Set of 3 stoneware mixing bowls (1.5, 2.5, 4-quart) with unique gradient glaze, non-slip base, and microwave, oven, and dishwasher safe design.',          129.99, 14],
            [14, 'oxo',            'baking-tools',       'Good Grips Measuring Cups',    'good-grips-measuring-cups',    '7-piece measuring cup set with angled easy-read markings, soft-grip handles, and nesting design for compact storage in any kitchen.',                       19.99,  35],
            [15, 'pyrex',          'baking-tools',       'Glass Casserole Dish Set',     'glass-casserole-dish-set',     'Set of 2 tempered glass casserole dishes (2-quart and 3-quart) with snap-lock lids, resistant to thermal shock, and freezer-to-oven versatility.',           39.99,  20],
            [16, 'le-creuset',     'baking-tools',       'Silicone Baking Mat Set',      'silicone-baking-mat-set',      'Set of 2 non-stick silicone baking mats (half-sheet size) with reinforced fiberglass core, temperature range -40°F to 480°F, and dishwasher safe.',          34.99,  25],
            // food-storage (3)
            [17, 'pyrex',          'food-storage',       'Glass Food Storage Set',       'glass-food-storage-set',       '18-piece tempered glass food storage set with BPA-free plastic lids, airtight seal, and stackable design for refrigerators, freezers, and microwaves.',        49.99,  30],
            [18, 'oxo',            'food-storage',       'Pop-Up Colander Set',          'pop-up-colander-set',          'Set of 2 collapsible colanders (2-quart and 4-quart) with pull-up handles, flip-down feeder legs, and space-saving design for easy draining.',             29.99,  20],
            [19, 'pyrex',          'food-storage',       'Snack & Dip Storage Containers','snack-dip-storage-containers','Set of 4 glass snack containers (2-cup each) with leak-proof lids, portion-control design, and microwave, freezer, and dishwasher safe glass.',             24.99,  25],
        ];

        foreach ($kitchen as [$i, $brandSlug, $catSlug, $name, $slug, $desc, $price, $stock]) {
            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $desc,
                    'price' => $price,
                    'stock' => $stock,
                    'image' => $img($i),
                    'status' => true,
                    'category_id' => $category($catSlug),
                    'brand_id' => $brand($brandSlug),
                ]
            );
        }
    }

    private function seedElectronicsFromDummyJson(): void
    {
        $categoryMap = [
            'smartphones' => 'smartphones-tablets',
            'laptops' => 'laptops-computers',
            'tablets' => 'smartphones-tablets',
            'audio' => 'audio-speakers',
            'cameras' => 'cameras-photography',
            'wearables' => 'wearables-smartwatches',
            'gaming' => 'gaming-gear',
            'mobile-accessories' => 'accessories',
            'laptop-accessories' => 'accessories',
        ];

        $categoryIds = Category::pluck('id', 'slug');

        foreach ($categoryMap as $dummyCat => $ourSlug) {
            $response = Http::timeout(10)->get("https://dummyjson.com/products/category/{$dummyCat}");

            if (!$response->successful()) {
                continue;
            }

            $data = $response->json();
            $products = $data['products'] ?? [];

            foreach ($products as $item) {
                $title = $item['title'] ?? 'Unknown';
                $slug = Str::slug($title);

                if (Product::whereSlug($slug)->exists()) {
                    continue;
                }

                $brandName = $item['brand'] ?? 'Generic';
                $brandSlug = Str::slug($brandName);

                $brand = Brand::firstOrCreate(
                    ['slug' => $brandSlug],
                    [
                        'name' => $brandName,
                        'description' => '',
                        'status' => true,
                    ]
                );

                $categoryId = $categoryIds[$ourSlug] ?? null;

                if (!$categoryId) {
                    continue;
                }

                Product::create([
                    'name' => $title,
                    'slug' => $slug,
                    'description' => $item['description'] ?? '',
                    'price' => ($item['price'] ?? 0) * 25400,
                    'stock' => $item['stock'] ?? rand(1, 50),
                    'image' => $item['thumbnail'] ?? '',
                    'status' => true,
                    'category_id' => $categoryId,
                    'brand_id' => $brand->id,
                ]);
            }
        }
    }
}
