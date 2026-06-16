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
        $this->seedTechProducts();
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
            '1507003211668-5e2e7e6e3f5a', '1603212842351-2585cc02d128',
            '1615360582858-04b1d0b1e5e1', '1571558562947-57798624b1e1',
            '1567620368345-8f8e7ac8d1b2', '1584308032187-93b1a3f6b2c3',
            '1546892570-9b5d4c3f8e1a', '1595181815028-5b4f0b4f7e2b',
            '1568294632819-3b7a5e6c1f4d', '1582813298127-5c9a3f2e8b6c',
            '1518843875459-f0b0d0e1b2a3', '1593151497289-8b2c1d4e5f6a',
            '1558641452-9b5d4c3f8e1a', '1580147562358-6b7c8d9e0f1a',
            '1548702358-2c3d4e5f6a7b', '1597536946301-8c9d0e1f2a3b',
            '1609741634068-9b8c7d6e5f4a', '1586962032210-7b6c5d4e3f2a',
            '1577242395138-5e4f3a2b1c0d', '1606812365498-7b6c5d4e3f2a',
            '1582813298127-5c9a3f2e8b6c', '1584308032187-93b1a3f6b2c3',
            '1546892570-9b5d4c3f8e1a', '1603212842351-2585cc02d128',
            '1558641452-9b5d4c3f8e1a', '1518843875459-f0b0d0e1b2a3',
            '1606812365498-7b6c5d4e3f2a', '1577242395138-5e4f3a2b1c0d',
            '1537293525435-9278d5b8e1c4', '1606913919536-0158f7f7b3e1',
            '1556909114-f6e7ad7d3136', '1610701596007-11502861dc1c',
            '1560343090-741c6e1b0f8b', '1594226801341-41427b4e8e4c',
        ];

        $img = fn (int $i) => "https://images.unsplash.com/photo-{$photos[$i]}?w=400&h=400&fit=crop&q=80";

        $kitchen = [
            // kitchen-appliances (8)
            [0,  'kitchenaid',     'kitchen-appliances', 'Professional Stand Mixer',     'professional-stand-mixer',     'Powerful 5-quart stand mixer with tilt-head design, 10 speeds, and planetary mixing action for perfect dough, batter, and whipped cream.',                 499.99, 15],
            [1,  'kitchenaid',     'kitchen-appliances', 'Artisan Blender',              'artisan-blender',              'High-performance blender with 1.5-litre pitcher, 7-speed control, and self-cleaning cycle for smoothies, soups, and frozen drinks.',                      249.99, 20],
            [2,  'cuisinart',      'kitchen-appliances', '14-Cup Food Processor',        '14-cup-food-processor',        'Extra-large 14-cup food processor with 720-watt motor, S-blade, slicing and shredding discs, and a dough blade for all your prep needs.',                  179.99, 12],
            [3,  'oxo',            'kitchen-appliances', 'Mandoline Slicer',             'mandoline-slicer',             'Adjustable mandoline slicer with four interchangeable blades, ergonomic handle, and a safety food holder for precision slicing.',                         69.99,  25],
            [4,  'kitchenaid',     'kitchen-appliances', 'Pro Line Toaster',             'pro-line-toaster',             '4-slot professional toaster with extra-wide slots, shade selector, countdown timer, and keep-warm function for perfectly toasted bagels and bread.',        179.99, 18],
            [5,  'cuisinart',      'kitchen-appliances', 'Coffee Maker Programmable',    'coffee-maker-programmable',    '14-cup programmable coffee maker with built-in grinder, brew-strength selector, auto-shutoff, and a gold-tone permanent filter for fresh coffee every morning.', 129.99, 22],
            [6,  'breville',       'kitchen-appliances', 'Electric Kettle',              'electric-kettle',              'Gooseneck electric kettle with variable temperature control, 1-litre capacity, and precision pour spout for pour-over coffee and tea enthusiasts.',          89.99,  30],
            [7,  'kitchenaid',     'kitchen-appliances', 'Immersion Blender',            'immersion-blender',            'Handheld immersion blender with 4-point stainless steel blade, variable speed trigger, and 8-inch shaft for blending soups and sauces directly in the pot.',  69.99,  25],
            // cookware (9)
            [8,  'cuisinart',      'cookware',           'Tri-Ply Stainless Cookware Set','tri-ply-stainless-cookware-set','10-piece tri-ply stainless steel cookware set with aluminum core, tempered glass lids, and riveted handles for even heat distribution.',                 399.99, 8],
            [9,  'lodge',          'cookware',           'Cast Iron Skillet',            'cast-iron-skillet',            'Pre-seasoned 12-inch cast iron skillet with superior heat retention, naturally non-stick surface, and oven-safe up to 500°F for searing and baking.',         44.99,  30],
            [10, 'staub',          'cookware',           'Round Cocotte Dutch Oven',      'round-cocotte-dutch-oven',     '5.5-quart enameled cast iron dutch oven with tight-fitting lid, self-basting spikes, and a smooth enamel interior that resists sticking.',                349.99, 10],
            [11, 'cuisinart',      'cookware',           'Stainless Saucepan Set',       'stainless-saucepan-set',       '3-piece stainless steel saucepan set (1.5, 2.5, 3.5-quart) with encapsulated base, cool-grip handles, and pour spouts for mess-free serving.',              129.99, 18],
            [12, 'staub',          'cookware',           'Non-Stick Fry Pan',            'non-stick-fry-pan',            '11-inch non-stick fry pan with PFOA-free coating, magnetic induction base, and ergonomic riveted stainless steel handle.',                                89.99,  22],
            [13, 'lodge',          'cookware',           'Cast Iron Griddle',            'cast-iron-griddle',            'Double-burner cast iron griddle pan with raised ridges, grease channel, and dual handles — perfect for pancakes, bacon, and grilled sandwiches.',            39.99,  20],
            [14, 'le-creuset',     'cookware',           'Enameled Braiser',             'enameled-braiser',             '3.5-quart enameled cast iron braiser with wide cooking surface, tight-fitting lid, and induction-ready base for slow braises and searing.',                 279.99, 12],
            [15, 'cuisinart',      'cookware',           'Stockpot with Lid',            'stockpot-with-lid',            '8-quart stainless steel stockpot with encapsulated base, straining lid, and cool-grip side handles for soups, stews, and pasta nights.',                     89.99,  15],
            [16, 'staub',          'cookware',           'Cast Iron Grill Pan',          'cast-iron-grill-pan',          '12-inch square cast iron grill pan with raised ridges, pour spouts, and a pre-seasoned surface for indoor grilling year-round.',                             99.99,  14],
            // tableware (8)
            [17, 'le-creuset',     'tableware',          'Stoneware Dinner Plate Set',   'stoneware-dinner-plate-set',   'Set of 4 stoneware dinner plates (10.75-inch) with hand-applied glaze, chip-resistant edge, and microwave, oven, and dishwasher safe construction.',         89.99,  20],
            [18, 'zwilling',       'tableware',          'Senso Wine Glasses Set',       'senso-wine-glasses-set',       'Set of 4 crystal wine glasses (550ml) with fine-rim design, lead-free crystal, and dishwasher-safe durability for everyday elegance.',                     59.99,  15],
            [19, 'le-creuset',     'tableware',          'Heritage Serving Bowl Set',    'heritage-serving-bowl-set',    'Set of 3 stoneware serving bowls (1.5, 2.5, 4-quart) with vibrant glaze, scalloped edges, and superior heat retention for family meals.',                 109.99, 12],
            [20, 'zwilling',       'tableware',          'Double-Wall Glass Mugs',       'double-wall-glass-mugs',       'Set of 2 double-wall borosilicate glass mugs (12oz) with vacuum insulation, heat-resistant design, and crystal-clear transparency for hot and cold beverages.', 39.99, 25],
            [21, 'le-creuset',     'tableware',          'Stoneware Cereal Bowls',       'stoneware-cereal-bowls',       'Set of 6 stoneware cereal bowls (6-inch) with classic scalloped edge, microwave-safe glaze, and stackable design for everyday dining elegance.',             79.99, 18],
            [22, 'zwilling',       'tableware',          'Glass Pitcher with Lid',       'glass-pitcher-with-lid',       '1.5-litre borosilicate glass pitcher with airtight lid, ergonomic handle, and fine-mesh infuser for iced tea, infused water, and lemonade.',                  34.99,  22],
            [23, 'le-creuset',     'tableware',          'Stoneware Salad Plates',       'stoneware-salad-plates',       'Set of 6 salad plates (8.5-inch) in matching stoneware finish with hand-painted glaze and chip-resistant edges for everyday dining.',                       74.99,  16],
            [24, 'zwilling',       'tableware',          'Espresso Cup Set',             'espresso-cup-set',             'Set of 6 espresso cups (3oz) with matching saucers in fine porcelain, stackable design, and dishwasher-safe finish for the perfect morning ritual.',          49.99,  20],
            // baking-tools (9)
            [25, 'oxo',            'baking-tools',       'Non-Stick Baking Sheet Set',   'non-stick-baking-sheet-set',   'Set of 3 non-stick baking sheets (full, half, quarter-sheet) with rolled edges, warp-resistant steel, and food-grade silicone coating.',                    44.99,  28],
            [26, 'le-creuset',     'baking-tools',       'Mixing Bowl Set',              'mixing-bowl-set',              'Set of 3 stoneware mixing bowls (1.5, 2.5, 4-quart) with unique gradient glaze, non-slip base, and microwave, oven, and dishwasher safe design.',          129.99, 14],
            [27, 'oxo',            'baking-tools',       'Good Grips Measuring Cups',    'good-grips-measuring-cups',    '7-piece measuring cup set with angled easy-read markings, soft-grip handles, and nesting design for compact storage in any kitchen.',                       19.99,  35],
            [28, 'pyrex',          'baking-tools',       'Glass Casserole Dish Set',     'glass-casserole-dish-set',     'Set of 2 tempered glass casserole dishes (2-quart and 3-quart) with snap-lock lids, resistant to thermal shock, and freezer-to-oven versatility.',           39.99,  20],
            [29, 'le-creuset',     'baking-tools',       'Silicone Baking Mat Set',      'silicone-baking-mat-set',      'Set of 2 non-stick silicone baking mats (half-sheet size) with reinforced fiberglass core, temperature range -40°F to 480°F, and dishwasher safe.',          34.99,  25],
            [30, 'oxo',            'baking-tools',       'Cooling Rack Set',             'cooling-rack-set',             'Set of 2 stainless steel cooling racks with nested grid design, dishwashersafe construction, and elevated feet for even air circulation.',                  24.99,  30],
            [31, 'pyrex',          'baking-tools',       'Loaf Pan Set',                 'loaf-pan-set',                 'Set of 3 glass loaf pans (5x9-inch) with tempered glass construction, pouring rims, and freezer-to-oven versatility for breads and meatloaf.',                29.99,  22],
            [32, 'le-creuset',     'baking-tools',       'Rectangular Baking Dish',      'rectangular-baking-dish',      '3-quart stoneware rectangular baking dish with hand-glazed finish, generous handles, and microwave, oven, broiler, and dishwasher safe durability.',          59.99,  18],
            [33, 'oxo',            'baking-tools',       'Pastry Blender Set',           'pastry-blender-set',           'Complete 6-piece pastry tool set with pastry blender, rolling pin, dough scraper, pastry brush, piping bags, and tips in a convenient storage case.',          34.99,  20],
            // food-storage (8)
            [34, 'pyrex',          'food-storage',       'Glass Food Storage Set',       'glass-food-storage-set',       '18-piece tempered glass food storage set with BPA-free plastic lids, airtight seal, and stackable design for refrigerators, freezers, and microwaves.',        49.99,  30],
            [35, 'oxo',            'food-storage',       'Pop-Up Colander Set',          'pop-up-colander-set',          'Set of 2 collapsible colanders (2-quart and 4-quart) with pull-up handles, flip-down feeder legs, and space-saving design for easy draining.',             29.99,  20],
            [36, 'pyrex',          'food-storage',       'Snack & Dip Storage Containers','snack-dip-storage-containers','Set of 4 glass snack containers (2-cup each) with leak-proof lids, portion-control design, and microwave, freezer, and dishwasher safe glass.',             24.99,  25],
            [37, 'oxo',            'food-storage',       'Fresh Produce Keeper Set',     'fresh-produce-keeper-set',     'Set of 3 produce keepers with adjustable vents, crisper tray, and BPA-free construction to extend fridge life of fruits, vegetables, and herbs.',               39.99,  18],
            [38, 'pyrex',          'food-storage',       'Simply Store Glass Set',       'simply-store-glass-set',       '10-piece glass storage set (1-cup, 2-cup, 4-cup) with snap-lock lids, airtight silicone seal, and rounded corners for easy cleaning and leftovers.',         34.99,  22],
            [39, 'oxo',            'food-storage',       'Airtight Dry Food Canisters',  'airtight-dry-food-canisters',  'Set of 4 airtight canisters (0.5, 1, 2, 3-litre) with push-button seal, clear body, and space-saving square design for pantry staples.',                     49.99,  15],
            [40, 'pyrex',          'food-storage',       'Easy Grab Glass Containers',   'easy-grab-glass-containers',   'Set of 6 round glass containers (1-cup and 2-cup) with easy-grip sides, coloured lids for identification, and leak-proof seal for on-the-go lunches.',         44.99,  20],
            [41, 'oxo',            'food-storage',       'Oil Dispenser Bottle',         'oil-dispenser-bottle',         '17oz glass oil dispenser with precision pour spout, flip-cap, and non-drip neck for olive oil, vegetable oil, and vinegar dispensing without mess.',            19.99,  28],
        ];

        // Register Breville brand if needed
        Brand::firstOrCreate(
            ['slug' => 'breville'],
            ['name' => 'Breville', 'description' => 'Premium kitchen appliances and espresso machines.', 'status' => true]
        );

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

        // Also fetch accessories for peripherals category
        foreach (['mobile-accessories', 'laptop-accessories'] as $dummyCat) {
            $response = Http::timeout(10)->get("https://dummyjson.com/products/category/{$dummyCat}");

            if (!$response->successful()) {
                continue;
            }

            $data = $response->json();
            $products = $data['products'] ?? [];

            foreach ($products as $item) {
                $title = $item['title'] ?? 'Unknown';
                $slug = Str::slug($title) . '-peripheral';

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

                $peripheralCatId = $categoryIds['peripherals'] ?? null;
                if (!$peripheralCatId) {
                    continue;
                }

                Product::create([
                    'name' => $title . ' (Peripheral)',
                    'slug' => $slug,
                    'description' => $item['description'] ?? '',
                    'price' => ($item['price'] ?? 0) * 25400,
                    'stock' => $item['stock'] ?? rand(1, 50),
                    'image' => $item['thumbnail'] ?? '',
                    'status' => true,
                    'category_id' => $peripheralCatId,
                    'brand_id' => $brand->id,
                ]);
            }
        }
    }

    private function seedTechProducts(): void
    {
        $category = fn (string $slug) => Category::whereSlug($slug)->value('id');
        $brand = fn (string $slug) => Brand::whereSlug($slug)->value('id');

        // Register brands that may not exist yet
        $techBrands = [
            ['sony', 'Sony Corporation', 'Japanese multinational conglomerate known for audio, video, and gaming electronics.'],
            ['bose', 'Bose Corporation', 'American manufacturer specializing in audio equipment and noise-cancelling headphones.'],
            ['jbl', 'JBL', 'American audio electronics company known for speakers and headphones.'],
            ['canon', 'Canon Inc.', 'Japanese multinational specializing in imaging and optical products, including cameras and printers.'],
            ['nikon', 'Nikon Corporation', 'Japanese multinational known for cameras, lenses, and optical instruments.'],
            ['fujifilm', 'Fujifilm Holdings', 'Japanese multinational known for photography, imaging, and optical products.'],
            ['fitbit', 'Fitbit Inc.', 'American company known for wearable fitness trackers and health monitoring devices.'],
            ['garmin', 'Garmin Ltd.', 'Swiss multinational known for GPS technology, wearables, and aviation electronics.'],
            ['razer', 'Razer Inc.', 'American-Singaporean multinational known for gaming hardware, peripherals, and software.'],
            ['corsair', 'Corsair Components', 'American computer peripherals and hardware company focused on gaming and enthusiast PCs.'],
            ['steelseries', 'SteelSeries', 'Danish company known for gaming peripherals such as headsets, keyboards, and mice.'],
            ['hyperx', 'HyperX', 'American gaming peripherals brand known for headphones, keyboards, and memory products.'],
            ['logitech', 'Logitech International', 'Swiss multinational known for computer peripherals, accessories, and gaming gear.'],
            ['microsoft', 'Microsoft Corporation', 'American multinational behind Surface devices, Xbox gaming, and productivity software.'],
        ];

        foreach ($techBrands as [$slug, $name, $desc]) {
            Brand::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => $desc, 'status' => true]
            );
        }

        $techPhotos = [
            'audio-speakers' => [
                '1505740420928-65eab1f1b6f8', '1545456200-1af3e99c0b3c',
                '1599669706099-0b4e1f8e5b7a', '1558081527-3f0c7d0b9e4d',
                '1589005125734-5c5b0f1a3a7c',
            ],
            'cameras-photography' => [
                '1516035069131-1f6b0b5e4f3a', '1554048612-b6a482bc6cf0',
                '1502920912153-9d5c8b2e8f1a', '1510127034140-0c5b3d4f8e2b',
                '1495706211718-1f7f3b6c9d0a',
            ],
            'wearables-smartwatches' => [
                '1523275335684-37898b6baf30', '1544890955-9b0e4b6f3c8a',
                '1508681934037-5f83f9b7e1c5', '1579584422851-3b8b6d0f4e2a',
                '1507608869123-1d5c6b8f4a2e',
            ],
            'gaming-gear' => [
                '1593305841986-0b6c8d4e1f2a', '1612289635358-3f0c7d5b9e4d',
                '1560253123-5c8f4b1a7e0d', '1582304254273-0c8f6b4a2e0d',
                '1611186871348-3f9c4e6b2a0d',
            ],
            'peripherals' => [
                '1618384887929-7e8b9c0f1a2d', '1587829741301-5c8f4b2e3a0d',
                '1618046214792-6c8f4b1a7e0d', '1593532843058-7e8b9c0f1a2d',
                '1546868871-af8b9c0d1e2f',
            ],
        ];

        $products = [
            // audio-speakers (5)
            ['sony', 'audio-speakers', 0, 'Sony WH-1000XM5', 'sony-wh-1000xm5', 'Industry-leading noise cancelling headphones with Auto NC Optimizer, 30-hour battery life, and crystal-clear hands-free calling.', 399.99, 25],
            ['bose', 'audio-speakers', 1, 'Bose QuietComfort Ultra', 'bose-quietcomfort-ultra', 'Premium wireless headphones with spatial audio, adjustable noise cancellation, and immersive sound powered by Bose CustomTune technology.', 429.99, 20],
            ['jbl', 'audio-speakers', 2, 'JBL Flip 6', 'jbl-flip-6', 'Portable Bluetooth speaker with punchy bass, IP67 waterproof design, and 12 hours of playtime for music anywhere.', 129.99, 35],
            ['sony', 'audio-speakers', 3, 'Sony Soundbar HT-A7000', 'sony-soundbar-ht-a7000', '7.1.2 channel Dolby Atmos soundbar with vertical surround engine, XR processor, and wireless subwoofer compatibility for cinematic sound.', 1399.99, 10],
            ['bose', 'audio-speakers', 4, 'Bose SoundLink Flex', 'bose-soundlink-flex', 'Portable Bluetooth speaker with PositionIQ technology, IP67 rating, and deep, clear sound for outdoor adventures.', 149.99, 30],
            // cameras-photography (5)
            ['canon', 'cameras-photography', 0, 'Canon EOS R6 Mark II', 'canon-eos-r6-mark-ii', 'Full-frame mirrorless camera with 24.2MP sensor, up to 40fps electronic shutter, 6K video recording, and advanced subject tracking AF.', 2499.99, 8],
            ['nikon', 'cameras-photography', 1, 'Nikon Z8', 'nikon-z8', 'High-resolution full-frame mirrorless camera with 45.7MP stacked CMOS sensor, 8K video, and EXPEED 7 image processor for professional-grade performance.', 3999.99, 5],
            ['sony', 'cameras-photography', 2, 'Sony Alpha A7 IV', 'sony-alpha-a7-iv', '33MP full-frame hybrid camera with real-time eye AF, 4K 60p video, S-Log3/S-Gamut3 colour profiles, and 5-axis in-body stabilization.', 2499.99, 10],
            ['fujifilm', 'cameras-photography', 3, 'Fujifilm X-T5', 'fujifilm-x-t5', 'Retro-styled APS-C mirrorless camera with 40MP X-Trans sensor, film simulation modes, and 6.2K video for creative photography enthusiasts.', 1799.99, 12],
            ['canon', 'cameras-photography', 4, 'Canon PowerShot G7 X Mark III', 'canon-powershot-g7-x-mark-iii', 'Compact point-and-shoot camera with 20.1MP 1-inch sensor, 4.2x optical zoom, 4K video, and live YouTube streaming capability.', 749.99, 15],
            // wearables-smartwatches (5)
            ['apple', 'wearables-smartwatches', 0, 'Apple Watch Ultra 2', 'apple-watch-ultra-2', 'Rugged titanium smartwatch with precision dual-frequency GPS, 100m water resistance, 36-hour battery, and advanced health sensors for extreme sports.', 799.99, 15],
            ['samsung', 'wearables-smartwatches', 1, 'Samsung Galaxy Watch 6 Pro', 'samsung-galaxy-watch-6-pro', 'Premium smartwatch with sapphire crystal, 47mm titanium case, body composition analysis, sleep coaching, and Wear OS with Google services.', 449.99, 18],
            ['fitbit', 'wearables-smartwatches', 2, 'Fitbit Charge 6', 'fitbit-charge-6', 'Advanced fitness tracker with built-in GPS, heart rate monitoring, stress management, Google integration, and 7-day battery life.', 159.99, 30],
            ['garmin', 'wearables-smartwatches', 3, 'Garmin Fenix 7', 'garmin-fenix-7', 'Multisport GPS smartwatch with rugged design, solar charging, topographical maps, training metrics, and up to 57 days of battery life.', 699.99, 12],
            ['apple', 'wearables-smartwatches', 4, 'Apple Watch Series 9', 'apple-watch-series-9', 'Stylish everyday smartwatch with S9 SiP chip, blood oxygen and ECG sensors, crash detection, and always-on Retina display.', 399.99, 25],
            // gaming-gear (5)
            ['razer', 'gaming-gear', 0, 'Razer DeathAdder V3 Pro', 'razer-deathadder-v3-pro', 'Ultra-lightweight wireless gaming mouse with 63g weight, Focus Pro 30K optical sensor, and 90-hour battery for competitive esports performance.', 149.99, 30],
            ['logitech', 'gaming-gear', 1, 'Logitech G Pro X Superlight', 'logitech-g-pro-x-superlight', 'Professional wireless gaming mouse weighing under 63g with HERO 25K sensor, hyper-fast scroll wheel, and zero-additive PTFE feet.', 159.99, 25],
            ['steelseries', 'gaming-gear', 2, 'SteelSeries Arctis Nova Pro', 'steelseries-arctis-nova-pro', 'Premium gaming headset with high-fidelity planar magnetic drivers, active noise cancellation, and simultaneous game-audio chat mixing.', 349.99, 15],
            ['corsair', 'gaming-gear', 3, 'Corsair K70 RGB Pro', 'corsair-k70-rgb-pro', 'Mechanical gaming keyboard with Cherry MX switches, aluminium frame, per-key RGB lighting, and tournament-grade 8,000Hz polling rate.', 189.99, 20],
            ['hyperx', 'gaming-gear', 4, 'HyperX Cloud Alpha Wireless', 'hyperx-cloud-alpha-wireless', 'Wireless gaming headset with 300-hour battery life, dual chamber drivers for clear audio, and memory foam comfort for marathon gaming sessions.', 199.99, 22],
            // peripherals (5)
            ['logitech', 'peripherals', 0, 'Logitech MX Master 3S', 'logitech-mx-master-3s', 'Advanced wireless mouse with 8K DPI optical sensor, quiet clicks, MagSpeed scroll wheel, and cross-device multi-computer workflow control.', 99.99, 30],
            ['razer', 'peripherals', 1, 'Razer BlackWidow V4 Pro', 'razer-blackwidow-v4-pro', 'Full-featured mechanical keyboard with Razer green switches, multi-function roller, USB pass-through, plush leatherette wrist rest, and Chroma RGB.', 249.99, 15],
            ['logitech', 'peripherals', 2, 'Logitech C920s Pro HD Webcam', 'logitech-c920s-pro-hd', 'Full HD 1080p webcam with dual omnidirectional microphones, Carl Zeiss optics, and privacy shutter for crystal-clear video calls and streaming.', 79.99, 35],
            ['microsoft', 'peripherals', 3, 'Microsoft Surface Arc Mouse', 'microsoft-surface-arc-mouse', 'Ultra-portable foldable mouse that snaps flat for travel, with full-size comfort, touch scroll strips, and Bluetooth connectivity for Windows and Mac.', 79.99, 20],
            ['logitech', 'peripherals', 4, 'Logitech G413 TKL SE', 'logitech-g413-tkl-se', 'Tenkeyless mechanical gaming keyboard with tactile switches, aircraft-grade aluminium alloy top case, and six-macro-key functionality for compact setups.', 119.99, 25],
        ];

        foreach ($products as [$brandSlug, $catSlug, $photoIdx, $name, $slug, $desc, $price, $stock]) {
            $catId = $category($catSlug);
            $brandId = $brand($brandSlug);

            if (!$catId || !$brandId) {
                continue;
            }

            $photos = $techPhotos[$catSlug] ?? [];
            $photo = isset($photos[$photoIdx]) ? "https://images.unsplash.com/photo-{$photos[$photoIdx]}?w=400&h=400&fit=crop&q=80" : '';

            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $desc,
                    'price' => $price,
                    'stock' => $stock,
                    'image' => $photo,
                    'status' => true,
                    'category_id' => $catId,
                    'brand_id' => $brandId,
                ]
            );
        }
    }
}
