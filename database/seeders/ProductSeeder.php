<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        $this->seedKitchenProducts();
        $this->seedOtherElectronics();

        $apiProductsSeeded = $this->seedElectronicsFromDummyJson();

        if ($apiProductsSeeded === 0) {
            Log::warning('API seeding returned 0 products due to network timeout or error. Falling back to offline electronics dataset.');
            $this->seedOfflineElectronics();
        }

        $this->seedAmazonScrapedProducts();
    }

    private function insertProducts(array $products): void
    {
        foreach ($products as $item) {
            $category = Category::where('slug', $item['category_slug'])->first();
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($item['brand_name'])],
                ['name' => $item['brand_name'], 'status' => true]
            );

            if ($category && $brand) {
                Product::firstOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                        'image' => $item['image'],
                        'status' => true,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );
            }
        }
    }

    private function seedKitchenProducts(): void
    {
        $products = [
            // Tableware
            ['name' => 'Stoneware Dinner Plate Set', 'slug' => 'stoneware-dinner-plate-set', 'description' => 'Complete 10-piece stoneware dinner plate set.', 'price' => 2285000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/stoneware,product?lock=62', 'category_slug' => 'tableware', 'brand_name' => 'Le Creuset'],
            ['name' => 'Heritage Serving Bowl Set', 'slug' => 'heritage-serving-bowl-set', 'description' => 'Premium ceramic serving bowls.', 'price' => 2793000, 'stock' => 10, 'image' => 'https://loremflickr.com/600/600/heritage,product?lock=63', 'category_slug' => 'tableware', 'brand_name' => 'Le Creuset'],
            ['name' => 'Stoneware Cereal Bowls', 'slug' => 'stoneware-cereal-bowls', 'description' => 'Durable stoneware cereal bowls.', 'price' => 2031000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/stoneware,product?lock=64', 'category_slug' => 'tableware', 'brand_name' => 'Le Creuset'],
            ['name' => 'Stoneware Salad Plates', 'slug' => 'stoneware-salad-plates', 'description' => 'Elegant salad plates for dining.', 'price' => 1904000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/stoneware,product?lock=65', 'category_slug' => 'tableware', 'brand_name' => 'Le Creuset'],
            ['name' => 'Classic White Mug Set', 'slug' => 'classic-white-mug-set', 'description' => 'Set of 6 classic white ceramic mugs.', 'price' => 850000, 'stock' => 40, 'image' => 'https://loremflickr.com/600/600/classic,product?lock=66', 'category_slug' => 'tableware', 'brand_name' => 'Corelle'],
            ['name' => 'Crystal Wine Glasses', 'slug' => 'crystal-wine-glasses', 'description' => 'Set of 4 elegant crystal wine glasses.', 'price' => 1500000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/crystal,product?lock=67', 'category_slug' => 'tableware', 'brand_name' => 'Riedel'],
            ['name' => 'Stainless Steel Cutlery Set', 'slug' => 'stainless-steel-cutlery-set', 'description' => '24-piece premium stainless steel cutlery set.', 'price' => 1200000, 'stock' => 50, 'image' => 'https://loremflickr.com/600/600/stainless,product?lock=68', 'category_slug' => 'tableware', 'brand_name' => 'WMF'],
            ['name' => 'Porcelain Soup Bowls', 'slug' => 'porcelain-soup-bowls', 'description' => 'Set of 4 fine porcelain soup bowls.', 'price' => 950000, 'stock' => 22, 'image' => 'https://loremflickr.com/600/600/porcelain,product?lock=69', 'category_slug' => 'tableware', 'brand_name' => 'Villeroy & Boch'],

            // Food Storage
            ['name' => 'Glass Food Storage Set', 'slug' => 'glass-food-storage-set', 'description' => 'Airtight glass food containers.', 'price' => 1269000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/glass,product?lock=72', 'category_slug' => 'food-storage', 'brand_name' => 'Pyrex'],
            ['name' => 'Snack & Dip Storage Containers', 'slug' => 'snack-dip-storage-containers', 'description' => 'Small glass containers with lids.', 'price' => 634000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/snack,product?lock=73', 'category_slug' => 'food-storage', 'brand_name' => 'Pyrex'],
            ['name' => 'Simply Store Glass Set', 'slug' => 'simply-store-glass-set', 'description' => 'Classic glass storage containers.', 'price' => 888000, 'stock' => 50, 'image' => 'https://loremflickr.com/600/600/simply,product?lock=74', 'category_slug' => 'food-storage', 'brand_name' => 'Pyrex'],
            ['name' => 'Easy Grab Glass Containers', 'slug' => 'easy-grab-glass-containers', 'description' => 'Convenient glass storage with easy-carry handles.', 'price' => 1142000, 'stock' => 12, 'image' => 'https://loremflickr.com/600/600/easy,product?lock=75', 'category_slug' => 'food-storage', 'brand_name' => 'Pyrex'],
            ['name' => 'Airtight Pantry Organizers', 'slug' => 'airtight-pantry-organizers', 'description' => 'Set of 5 clear pantry organizers with airtight lids.', 'price' => 1500000, 'stock' => 60, 'image' => 'https://loremflickr.com/600/600/airtight,product?lock=76', 'category_slug' => 'food-storage', 'brand_name' => 'OXO'],
            ['name' => 'Silicone Food Bags', 'slug' => 'silicone-food-bags', 'description' => 'Reusable silicone food storage bags.', 'price' => 450000, 'stock' => 100, 'image' => 'https://loremflickr.com/600/600/silicone,product?lock=77', 'category_slug' => 'food-storage', 'brand_name' => 'Stasher'],
            ['name' => 'Bento Lunch Box', 'slug' => 'bento-lunch-box', 'description' => 'Stainless steel bento box for meals on the go.', 'price' => 550000, 'stock' => 35, 'image' => 'https://loremflickr.com/600/600/bento,product?lock=78', 'category_slug' => 'food-storage', 'brand_name' => 'Bentgo'],
            ['name' => 'Vacuum Sealer Bags Set', 'slug' => 'vacuum-sealer-bags-set', 'description' => '50-pack vacuum sealer bags for long term storage.', 'price' => 350000, 'stock' => 80, 'image' => 'https://loremflickr.com/600/600/vacuum,product?lock=79', 'category_slug' => 'food-storage', 'brand_name' => 'FoodSaver'],

            // Kitchen Appliances
            ['name' => 'Pro Stand Mixer', 'slug' => 'pro-stand-mixer', 'description' => 'Professional grade stand mixer with 5L bowl.', 'price' => 8500000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/pro,product?lock=82', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'KitchenAid'],
            ['name' => 'Smart Coffee Maker', 'slug' => 'smart-coffee-maker', 'description' => 'Wi-Fi enabled drip coffee maker.', 'price' => 3200000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/smart,product?lock=83', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Breville'],
            ['name' => 'Digital Air Fryer', 'slug' => 'digital-air-fryer', 'description' => '6L digital air fryer with 8 presets.', 'price' => 2500000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/digital,product?lock=84', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Philips'],
            ['name' => 'High-Speed Blender', 'slug' => 'high-speed-blender', 'description' => '1500W high-speed blender for smoothies and soups.', 'price' => 4500000, 'stock' => 12, 'image' => 'https://loremflickr.com/600/600/high-speed,product?lock=85', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Vitamix'],
            ['name' => 'Multi-Cooker Pro', 'slug' => 'multi-cooker-pro', 'description' => '9-in-1 electric pressure cooker and slow cooker.', 'price' => 3000000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/multi-cooker,product?lock=86', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Instant Pot'],
            ['name' => 'Toaster Oven', 'slug' => 'toaster-oven', 'description' => 'Compact convection toaster oven.', 'price' => 1800000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/toaster,product?lock=87', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Cuisinart'],
            ['name' => 'Electric Kettle', 'slug' => 'electric-kettle', 'description' => 'Variable temperature electric kettle.', 'price' => 1200000, 'stock' => 50, 'image' => 'https://loremflickr.com/600/600/electric,product?lock=88', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Fellow'],
            ['name' => 'Juicer Extractor', 'slug' => 'juicer-extractor', 'description' => 'Cold press juicer extractor.', 'price' => 3500000, 'stock' => 18, 'image' => 'https://loremflickr.com/600/600/juicer,product?lock=89', 'category_slug' => 'kitchen-appliances', 'brand_name' => 'Hurom'],

            // Cookware
            ['name' => 'Non-Stick Frying Pan', 'slug' => 'non-stick-frying-pan', 'description' => '12-inch premium non-stick frying pan.', 'price' => 1500000, 'stock' => 40, 'image' => 'https://loremflickr.com/600/600/non-stick,product?lock=92', 'category_slug' => 'cookware', 'brand_name' => 'T-fal'],
            ['name' => 'Stainless Steel Saucepan', 'slug' => 'stainless-steel-saucepan', 'description' => '2-quart stainless steel saucepan with lid.', 'price' => 1800000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/stainless,product?lock=93', 'category_slug' => 'cookware', 'brand_name' => 'All-Clad'],
            ['name' => 'Cast Iron Skillet', 'slug' => 'cast-iron-skillet', 'description' => '10-inch pre-seasoned cast iron skillet.', 'price' => 900000, 'stock' => 60, 'image' => 'https://loremflickr.com/600/600/cast,product?lock=94', 'category_slug' => 'cookware', 'brand_name' => 'Lodge'],
            ['name' => 'Ceramic Dutch Oven', 'slug' => 'ceramic-dutch-oven', 'description' => '5.5-quart enameled cast iron dutch oven.', 'price' => 5500000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/ceramic,product?lock=95', 'category_slug' => 'cookware', 'brand_name' => 'Le Creuset'],
            ['name' => 'Wok Pan', 'slug' => 'wok-pan', 'description' => '14-inch carbon steel wok.', 'price' => 1200000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/wok,product?lock=96', 'category_slug' => 'cookware', 'brand_name' => 'Joyce Chen'],
            ['name' => 'Copper Chef Pan', 'slug' => 'copper-chef-pan', 'description' => '10-inch copper core frying pan.', 'price' => 2200000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/copper,product?lock=97', 'category_slug' => 'cookware', 'brand_name' => 'Mauviel'],
            ['name' => 'Stock Pot', 'slug' => 'stock-pot', 'description' => '8-quart stainless steel stock pot.', 'price' => 1900000, 'stock' => 22, 'image' => 'https://loremflickr.com/600/600/stock,product?lock=98', 'category_slug' => 'cookware', 'brand_name' => 'Cuisinart'],
            ['name' => 'Grill Pan', 'slug' => 'grill-pan', 'description' => 'Square cast iron grill pan.', 'price' => 1100000, 'stock' => 35, 'image' => 'https://loremflickr.com/600/600/grill,product?lock=99', 'category_slug' => 'cookware', 'brand_name' => 'Lodge'],

            // Baking Tools
            ['name' => 'Silicone Baking Mat', 'slug' => 'silicone-baking-mat', 'description' => 'Non-stick silicone baking mat (Set of 2).', 'price' => 450000, 'stock' => 100, 'image' => 'https://loremflickr.com/600/600/silicone,product?lock=102', 'category_slug' => 'baking-tools', 'brand_name' => 'Silpat'],
            ['name' => 'Heavy Duty Baking Sheet', 'slug' => 'heavy-duty-baking-sheet', 'description' => 'Commercial grade aluminum baking sheet.', 'price' => 600000, 'stock' => 50, 'image' => 'https://loremflickr.com/600/600/heavy,product?lock=103', 'category_slug' => 'baking-tools', 'brand_name' => 'Nordic Ware'],
            ['name' => 'Springform Pan', 'slug' => 'springform-pan', 'description' => '9-inch non-stick springform pan.', 'price' => 550000, 'stock' => 40, 'image' => 'https://loremflickr.com/600/600/springform,product?lock=104', 'category_slug' => 'baking-tools', 'brand_name' => 'Wilton'],
            ['name' => 'Stainless Measuring Cups', 'slug' => 'stainless-measuring-cups', 'description' => 'Set of 6 stainless steel measuring cups.', 'price' => 350000, 'stock' => 80, 'image' => 'https://loremflickr.com/600/600/stainless,product?lock=105', 'category_slug' => 'baking-tools', 'brand_name' => 'OXO'],
            ['name' => 'Digital Kitchen Scale', 'slug' => 'digital-kitchen-scale', 'description' => 'High precision digital kitchen scale.', 'price' => 750000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/digital,product?lock=106', 'category_slug' => 'baking-tools', 'brand_name' => 'Escali'],
            ['name' => 'Rolling Pin', 'slug' => 'rolling-pin', 'description' => 'Classic wooden rolling pin.', 'price' => 300000, 'stock' => 60, 'image' => 'https://loremflickr.com/600/600/rolling,product?lock=107', 'category_slug' => 'baking-tools', 'brand_name' => 'JK Adams'],
            ['name' => 'Muffin Pan', 'slug' => 'muffin-pan', 'description' => '12-cup non-stick muffin pan.', 'price' => 450000, 'stock' => 55, 'image' => 'https://loremflickr.com/600/600/muffin,product?lock=108', 'category_slug' => 'baking-tools', 'brand_name' => 'USA Pan'],
            ['name' => 'Piping Bag Set', 'slug' => 'piping-bag-set', 'description' => 'Reusable piping bags with 24 nozzles.', 'price' => 500000, 'stock' => 70, 'image' => 'https://loremflickr.com/600/600/piping,product?lock=109', 'category_slug' => 'baking-tools', 'brand_name' => 'Ateco']
        ];

        $this->insertProducts($products);
    }

    private function seedOtherElectronics(): void
    {
        $products = [
            // Audio & Speakers
            ['name' => 'Sony WH-1000XM5 Headphones', 'slug' => 'sony-wh-1000xm5-headphones', 'description' => 'Industry-leading noise cancellation, 30-hour battery, crystal-clear hands-free calling, and lightweight ergonomic design.', 'price' => 10159000, 'stock' => 35, 'image' => 'https://loremflickr.com/600/600/sony,product?lock=119', 'category_slug' => 'audio-speakers', 'brand_name' => 'Sony'],
            ['name' => 'Apple AirPods Pro 2', 'slug' => 'apple-airpods-pro-2', 'description' => 'Adaptive audio, active noise cancellation, personalised spatial audio, USB-C charging case with Find My support.', 'price' => 7999000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=120', 'category_slug' => 'audio-speakers', 'brand_name' => 'Apple'],
            ['name' => 'Bose QuietComfort Ultra', 'slug' => 'bose-quietcomfort-ultra', 'description' => 'Spatial audio, CustomTune noise cancellation, immersiv feel, and 24-hour battery life for premium wireless listening.', 'price' => 10919000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/bose,product?lock=121', 'category_slug' => 'audio-speakers', 'brand_name' => 'Bose'],
            ['name' => 'Volt Phase Pro Speaker', 'slug' => 'volt-phase-pro-speaker', 'description' => 'Cozy smart speaker with crystal clear 360-degree audio and multi-room support.', 'price' => 820000, 'stock' => 40, 'image' => 'https://loremflickr.com/600/600/volt,product?lock=122', 'category_slug' => 'audio-speakers', 'brand_name' => 'Volt'],
            ['name' => 'JBL Charge 5', 'slug' => 'jbl-charge-5', 'description' => 'Portable Bluetooth speaker with IP67 waterproof and dustproof rating.', 'price' => 3500000, 'stock' => 60, 'image' => 'https://loremflickr.com/600/600/jbl,product?lock=123', 'category_slug' => 'audio-speakers', 'brand_name' => 'JBL'],
            ['name' => 'Sonos Era 100', 'slug' => 'sonos-era-100', 'description' => 'Smart speaker for room-filling sound with voice control.', 'price' => 6500000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/sonos,product?lock=124', 'category_slug' => 'audio-speakers', 'brand_name' => 'Sonos'],
            ['name' => 'Sennheiser Momentum 4', 'slug' => 'sennheiser-momentum-4', 'description' => 'Premium wireless over-ear headphones with exceptional sound.', 'price' => 8900000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/sennheiser,product?lock=125', 'category_slug' => 'audio-speakers', 'brand_name' => 'Sennheiser'],
            ['name' => 'Marshall Emberton II', 'slug' => 'marshall-emberton-ii', 'description' => 'Compact portable speaker with signature Marshall sound.', 'price' => 4200000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/marshall,product?lock=126', 'category_slug' => 'audio-speakers', 'brand_name' => 'Marshall'],

            // Wearables & Smartwatches
            ['name' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2', 'description' => 'Titanium case, precision dual-frequency GPS, 100m water resistance, 36-hour battery, and advanced health sensors.', 'price' => 20319000, 'stock' => 14, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=129', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Apple'],
            ['name' => 'Samsung Galaxy Watch 6 Pro', 'slug' => 'samsung-galaxy-watch-6-pro', 'description' => '47mm titanium case, sapphire crystal, body composition analysis, sleep coaching, and Wear OS with Google services.', 'price' => 11419000, 'stock' => 18, 'image' => 'https://loremflickr.com/600/600/samsung,product?lock=130', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Samsung'],
            ['name' => 'Orbit Halo Watch', 'slug' => 'orbit-halo-watch', 'description' => 'Premium smartwatch with active health tracking, built-in GPS, and 14-day battery life.', 'price' => 11999000, 'stock' => 10, 'image' => 'https://loremflickr.com/600/600/orbit,product?lock=131', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Orbit'],
            ['name' => 'Garmin Fenix 7X', 'slug' => 'garmin-fenix-7x', 'description' => 'Multisport GPS watch with solar charging capabilities.', 'price' => 22000000, 'stock' => 12, 'image' => 'https://loremflickr.com/600/600/garmin,product?lock=132', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Garmin'],
            ['name' => 'Fitbit Charge 6', 'slug' => 'fitbit-charge-6', 'description' => 'Advanced fitness and health tracker with built-in GPS.', 'price' => 4500000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/fitbit,product?lock=133', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Fitbit'],
            ['name' => 'Apple Watch Series 9', 'slug' => 'apple-watch-series-9', 'description' => 'Advanced health sensors, Always-On Retina display, and Double Tap gesture.', 'price' => 10999000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=134', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Apple'],
            ['name' => 'Pixel Watch 2', 'slug' => 'pixel-watch-2', 'description' => 'Google engineered smartwatch with Fitbit health tracking.', 'price' => 8500000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/pixel,product?lock=135', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Google'],
            ['name' => 'Suunto 9 Baro', 'slug' => 'suunto-9-baro', 'description' => 'Durable GPS sports watch with long battery life.', 'price' => 14000000, 'stock' => 8, 'image' => 'https://loremflickr.com/600/600/suunto,product?lock=136', 'category_slug' => 'wearables-smartwatches', 'brand_name' => 'Suunto'],

            // Cameras & Photography
            ['name' => 'Sony A7 IV', 'slug' => 'sony-a7-iv', 'description' => 'Full-frame mirrorless interchangeable lens camera.', 'price' => 55000000, 'stock' => 10, 'image' => 'https://loremflickr.com/600/600/sony,product?lock=139', 'category_slug' => 'cameras-photography', 'brand_name' => 'Sony'],
            ['name' => 'Canon EOS R6 Mark II', 'slug' => 'canon-eos-r6-mark-ii', 'description' => 'High-performance hybrid mirrorless camera.', 'price' => 62000000, 'stock' => 8, 'image' => 'https://loremflickr.com/600/600/canon,product?lock=140', 'category_slug' => 'cameras-photography', 'brand_name' => 'Canon'],
            ['name' => 'Nikon Z8', 'slug' => 'nikon-z8', 'description' => 'Professional mirrorless camera with 8K video.', 'price' => 95000000, 'stock' => 5, 'image' => 'https://loremflickr.com/600/600/nikon,product?lock=141', 'category_slug' => 'cameras-photography', 'brand_name' => 'Nikon'],
            ['name' => 'Fujifilm X-T5', 'slug' => 'fujifilm-x-t5', 'description' => 'APS-C mirrorless camera with vintage design.', 'price' => 42000000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/fujifilm,product?lock=142', 'category_slug' => 'cameras-photography', 'brand_name' => 'Fujifilm'],
            ['name' => 'GoPro Hero 12 Black', 'slug' => 'gopro-hero-12-black', 'description' => 'Waterproof action camera with 5.3K video.', 'price' => 10500000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/gopro,product?lock=143', 'category_slug' => 'cameras-photography', 'brand_name' => 'GoPro'],
            ['name' => 'DJI Mini 4 Pro', 'slug' => 'dji-mini-4-pro', 'description' => 'Lightweight drone with omnidirectional obstacle sensing.', 'price' => 25000000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/dji,product?lock=144', 'category_slug' => 'cameras-photography', 'brand_name' => 'DJI'],
            ['name' => 'Polaroid Now+', 'slug' => 'polaroid-now-plus', 'description' => 'Bluetooth connected analog instant camera.', 'price' => 4500000, 'stock' => 40, 'image' => 'https://loremflickr.com/600/600/polaroid,product?lock=145', 'category_slug' => 'cameras-photography', 'brand_name' => 'Polaroid'],
            ['name' => 'Sigma 24-70mm f/2.8', 'slug' => 'sigma-24-70mm-f28', 'description' => 'Standard zoom lens for full-frame mirrorless cameras.', 'price' => 28000000, 'stock' => 12, 'image' => 'https://loremflickr.com/600/600/sigma,product?lock=146', 'category_slug' => 'cameras-photography', 'brand_name' => 'Sigma'],

            // Gaming Gear
            ['name' => 'PlayStation 5 Pro', 'slug' => 'playstation-5-pro', 'description' => 'Next-gen gaming console with 8K support and ray tracing.', 'price' => 18000000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/playstation,product?lock=149', 'category_slug' => 'gaming-gear', 'brand_name' => 'Sony'],
            ['name' => 'Xbox Series X', 'slug' => 'xbox-series-x', 'description' => 'Fastest, most powerful Xbox ever.', 'price' => 15500000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/xbox,product?lock=150', 'category_slug' => 'gaming-gear', 'brand_name' => 'Microsoft'],
            ['name' => 'Nintendo Switch OLED', 'slug' => 'nintendo-switch-oled', 'description' => 'Hybrid console with vibrant 7-inch OLED screen.', 'price' => 8500000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/nintendo,product?lock=151', 'category_slug' => 'gaming-gear', 'brand_name' => 'Nintendo'],
            ['name' => 'Steam Deck OLED', 'slug' => 'steam-deck-oled', 'description' => 'Handheld PC gaming console with HDR OLED display.', 'price' => 16500000, 'stock' => 10, 'image' => 'https://loremflickr.com/600/600/steam,product?lock=152', 'category_slug' => 'gaming-gear', 'brand_name' => 'Valve'],
            ['name' => 'Razer Basilisk V3 Pro', 'slug' => 'razer-basilisk-v3-pro', 'description' => 'Customizable wireless gaming mouse.', 'price' => 4200000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/razer,product?lock=153', 'category_slug' => 'gaming-gear', 'brand_name' => 'Razer'],
            ['name' => 'Logitech G Pro X Superlight', 'slug' => 'logitech-g-pro-x-superlight', 'description' => 'Ultra-lightweight wireless gaming mouse.', 'price' => 3800000, 'stock' => 50, 'image' => 'https://loremflickr.com/600/600/logitech,product?lock=154', 'category_slug' => 'gaming-gear', 'brand_name' => 'Logitech'],
            ['name' => 'SteelSeries Arctis Nova Pro', 'slug' => 'steelseries-arctis-nova-pro', 'description' => 'Premium wireless gaming headset with active noise cancellation.', 'price' => 8500000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/steelseries,product?lock=155', 'category_slug' => 'gaming-gear', 'brand_name' => 'SteelSeries'],
            ['name' => 'Corsair K100 RGB', 'slug' => 'corsair-k100-rgb', 'description' => 'Optical-mechanical gaming keyboard.', 'price' => 5500000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/corsair,product?lock=156', 'category_slug' => 'gaming-gear', 'brand_name' => 'Corsair'],

            // Peripherals
            ['name' => 'Keychron Q1 Pro', 'slug' => 'keychron-q1-pro', 'description' => 'Wireless custom mechanical keyboard.', 'price' => 4800000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/keychron,product?lock=159', 'category_slug' => 'peripherals', 'brand_name' => 'Keychron'],
            ['name' => 'Logitech MX Master 3S', 'slug' => 'logitech-mx-master-3s', 'description' => 'Advanced wireless mouse for productivity.', 'price' => 2500000, 'stock' => 60, 'image' => 'https://loremflickr.com/600/600/logitech,product?lock=160', 'category_slug' => 'peripherals', 'brand_name' => 'Logitech'],
            ['name' => 'Dell UltraSharp 32 4K', 'slug' => 'dell-ultrasharp-32-4k', 'description' => '32-inch 4K USB-C Hub Monitor.', 'price' => 21000000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/dell,product?lock=161', 'category_slug' => 'peripherals', 'brand_name' => 'Dell'],
            ['name' => 'LG 27" UltraGear OLED', 'slug' => 'lg-27-ultragear-oled', 'description' => '1440p 240Hz OLED gaming monitor.', 'price' => 25000000, 'stock' => 10, 'image' => 'https://loremflickr.com/600/600/lg,product?lock=162', 'category_slug' => 'peripherals', 'brand_name' => 'LG'],
            ['name' => 'Elgato Stream Deck MK.2', 'slug' => 'elgato-stream-deck-mk2', 'description' => 'Studio controller with 15 macro keys.', 'price' => 3800000, 'stock' => 35, 'image' => 'https://loremflickr.com/600/600/elgato,product?lock=163', 'category_slug' => 'peripherals', 'brand_name' => 'Elgato'],
            ['name' => 'Anker 778 Thunderbolt 4 Dock', 'slug' => 'anker-778-thunderbolt-4-dock', 'description' => '12-in-1 Thunderbolt 4 docking station.', 'price' => 8500000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/anker,product?lock=164', 'category_slug' => 'peripherals', 'brand_name' => 'Anker'],
            ['name' => 'Wacom Intuos Pro', 'slug' => 'wacom-intuos-pro', 'description' => 'Creative pen tablet for professional artists.', 'price' => 9500000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/wacom,product?lock=165', 'category_slug' => 'peripherals', 'brand_name' => 'Wacom'],
            ['name' => 'Blue Yeti USB Microphone', 'slug' => 'blue-yeti-usb-microphone', 'description' => 'Professional multi-pattern USB microphone.', 'price' => 3200000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/blue,product?lock=166', 'category_slug' => 'peripherals', 'brand_name' => 'Blue']
        ];

        $this->insertProducts($products);
    }

    private function seedElectronicsFromDummyJson(): int
    {
        $apiCategories = [
            'laptops' => 'laptops-computers',
            'smartphones' => 'smartphones-tablets',
            'mobile-accessories' => 'accessories'
        ];

        $seededCount = 0;

        foreach ($apiCategories as $dummyCat => $localSlug) {
            $category = Category::where('slug', $localSlug)->first();
            if (!$category) continue;

            $url = "https://dummyjson.com/products/category/{$dummyCat}";

            try {
                $response = Http::timeout(6)->retry(2, 1000)->get($url);

                if ($response->successful()) {
                    $products = $response->json()['products'] ?? [];

                    foreach ($products as $item) {
                        $brandName = $item['brand'] ?? 'Generic';
                        $brandSlug = Str::slug($brandName);

                        $brand = Brand::firstOrCreate(
                            ['slug' => $brandSlug],
                            ['name' => $brandName, 'status' => true]
                        );

                        Product::firstOrCreate(
                            ['slug' => Str::slug($item['title'])],
                            [
                                'name' => $item['title'],
                                'description' => $item['description'] ?? 'No description available.',
                                'price' => round((($item['price'] ?? 100) * 25400), -3),
                                'stock' => $item['stock'] ?? 10,
                                'image' => $item['thumbnail'] ?? 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=600&q=80&auto=format',
                                'status' => true,
                                'category_id' => $category->id,
                                'brand_id' => $brand->id,
                            ]
                        );
                        $seededCount++;
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Failed to fetch from DummyJSON for category {$dummyCat}: " . $e->getMessage());
            }
        }

        return $seededCount;
    }

    private function seedOfflineElectronics(): void
    {
        $products = [
            // Laptops & Computers
            ['name' => 'Apple MacBook Pro 16', 'slug' => 'apple-macbook-pro-16', 'description' => 'M3 Max chip, 48GB unified memory, 1TB SSD, 16-inch Liquid Retina XDR display with 22-hour battery life for pro workflows.', 'price' => 69999000, 'stock' => 12, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=231', 'category_slug' => 'laptops-computers', 'brand_name' => 'Apple'],
            ['name' => 'Dell XPS 15', 'slug' => 'dell-xps-15', 'description' => '13th Gen Intel Core i7, 16GB RAM, 512GB SSD, 15.6-inch OLED InfinityEdge display with stunning colour accuracy.', 'price' => 45999000, 'stock' => 8, 'image' => 'https://loremflickr.com/600/600/dell,product?lock=232', 'category_slug' => 'laptops-computers', 'brand_name' => 'Dell'],
            ['name' => 'Samsung Galaxy Book 3 Ultra', 'slug' => 'samsung-galaxy-book-3-ultra', 'description' => 'Intel Core i9, NVIDIA RTX 4070, 32GB RAM, 16-inch AMOLED display with 120Hz refresh rate.', 'price' => 53999000, 'stock' => 6, 'image' => 'https://loremflickr.com/600/600/samsung,product?lock=233', 'category_slug' => 'laptops-computers', 'brand_name' => 'Samsung'],
            ['name' => 'Apex Nova 14 Ultra', 'slug' => 'apex-nova-14-ultra', 'description' => 'Neural-class M-Series 32GB laptop with 14-inch OLED display and all-day battery life.', 'price' => 32490000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/apex,product?lock=234', 'category_slug' => 'laptops-computers', 'brand_name' => 'Apex'],
            ['name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'slug' => 'lenovo-thinkpad-x1-carbon', 'description' => 'Business ultrabook with legendary keyboard and robust security.', 'price' => 40000000, 'stock' => 20, 'image' => 'https://loremflickr.com/600/600/lenovo,product?lock=235', 'category_slug' => 'laptops-computers', 'brand_name' => 'Lenovo'],
            ['name' => 'ASUS ROG Zephyrus G14', 'slug' => 'asus-rog-zephyrus-g14', 'description' => 'Compact gaming laptop with AniMe Matrix display.', 'price' => 38000000, 'stock' => 18, 'image' => 'https://loremflickr.com/600/600/asus,product?lock=236', 'category_slug' => 'laptops-computers', 'brand_name' => 'ASUS'],
            ['name' => 'HP Spectre x360 14', 'slug' => 'hp-spectre-x360-14', 'description' => 'Premium 2-in-1 convertible laptop with stunning design.', 'price' => 35000000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/hp,product?lock=237', 'category_slug' => 'laptops-computers', 'brand_name' => 'HP'],
            ['name' => 'Microsoft Surface Pro 9', 'slug' => 'microsoft-surface-pro-9', 'description' => 'Versatile tablet-laptop hybrid with touchscreen.', 'price' => 28000000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/microsoft,product?lock=238', 'category_slug' => 'laptops-computers', 'brand_name' => 'Microsoft'],

            // Smartphones & Tablets
            ['name' => 'Apple iPhone 15 Pro Max', 'slug' => 'apple-iphone-15-pro-max', 'description' => 'A17 Pro chip, 48MP pro camera system, 6.7-inch Super Retina XDR display, titanium design with 5G connectivity.', 'price' => 34999000, 'stock' => 30, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=241', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Apple'],
            ['name' => 'Samsung Galaxy S24 Ultra', 'slug' => 'samsung-galaxy-s24-ultra', 'description' => 'Snapdragon 8 Gen 3, 200MP camera with AI-powered editing, S Pen, titanium frame, and Galaxy AI features.', 'price' => 29999000, 'stock' => 22, 'image' => 'https://loremflickr.com/600/600/samsung,product?lock=242', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Samsung'],
            ['name' => 'Nimbus Pulse X1', 'slug' => 'nimbus-pulse-x1', 'description' => 'Flagship smartphone with 5G connectivity and 200MP camera system.', 'price' => 24999000, 'stock' => 25, 'image' => 'https://loremflickr.com/600/600/nimbus,product?lock=243', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Nimbus'],
            ['name' => 'Apple iPad Air M2', 'slug' => 'apple-ipad-air-m2', 'description' => 'M2 chip, 11-inch Liquid Retina display, 256GB storage, Apple Pencil Pro support, and all-day battery life.', 'price' => 19999000, 'stock' => 18, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=244', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Apple'],
            ['name' => 'Samsung Galaxy Tab S9 Ultra', 'slug' => 'samsung-galaxy-tab-s9-ultra', 'description' => '14.6-inch Dynamic AMOLED display, Snapdragon 8 Gen 2, IP68 water resistance, S Pen included.', 'price' => 27999000, 'stock' => 10, 'image' => 'https://loremflickr.com/600/600/samsung,product?lock=245', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Samsung'],
            ['name' => 'Google Pixel 8 Pro', 'slug' => 'google-pixel-8-pro', 'description' => 'Google Tensor G3, advanced AI camera features, and clean Android experience.', 'price' => 23500000, 'stock' => 28, 'image' => 'https://loremflickr.com/600/600/google,product?lock=246', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Google'],
            ['name' => 'OnePlus 12', 'slug' => 'oneplus-12', 'description' => 'Flagship killer with ultra-fast charging and Hasselblad cameras.', 'price' => 21000000, 'stock' => 35, 'image' => 'https://loremflickr.com/600/600/oneplus,product?lock=247', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'OnePlus'],
            ['name' => 'iPad Pro 12.9" M2', 'slug' => 'ipad-pro-12-9', 'description' => 'Ultimate tablet with Liquid Retina XDR display and M2 chip.', 'price' => 32000000, 'stock' => 15, 'image' => 'https://loremflickr.com/600/600/ipad,product?lock=248', 'category_slug' => 'smartphones-tablets', 'brand_name' => 'Apple'],

            // Accessories
            ['name' => 'Apple MagSafe Charger', 'slug' => 'apple-magsafe-charger', 'description' => 'Fast wireless charging for iPhone 12 and newer.', 'price' => 1200000, 'stock' => 100, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=251', 'category_slug' => 'accessories', 'brand_name' => 'Apple'],
            ['name' => 'Anker PowerCore 20K', 'slug' => 'anker-powercore-20k', 'description' => '20,000mAh portable charger with USB-C PD.', 'price' => 1500000, 'stock' => 80, 'image' => 'https://loremflickr.com/600/600/anker,product?lock=252', 'category_slug' => 'accessories', 'brand_name' => 'Anker'],
            ['name' => 'Belkin 3-in-1 Wireless Charger', 'slug' => 'belkin-3-in-1', 'description' => 'Charge iPhone, Apple Watch, and AirPods simultaneously.', 'price' => 3500000, 'stock' => 45, 'image' => 'https://loremflickr.com/600/600/belkin,product?lock=253', 'category_slug' => 'accessories', 'brand_name' => 'Belkin'],
            ['name' => 'OtterBox Defender Series', 'slug' => 'otterbox-defender', 'description' => 'Rugged protection case for smartphones.', 'price' => 1500000, 'stock' => 60, 'image' => 'https://loremflickr.com/600/600/otterbox,product?lock=254', 'category_slug' => 'accessories', 'brand_name' => 'OtterBox'],
            ['name' => 'Nomad Modern Leather Case', 'slug' => 'nomad-modern-leather', 'description' => 'Premium Horween leather case.', 'price' => 1800000, 'stock' => 40, 'image' => 'https://loremflickr.com/600/600/nomad,product?lock=255', 'category_slug' => 'accessories', 'brand_name' => 'Nomad'],
            ['name' => 'Spigen Tough Armor', 'slug' => 'spigen-tough-armor', 'description' => 'Dual-layer protective case with kickstand.', 'price' => 850000, 'stock' => 120, 'image' => 'https://loremflickr.com/600/600/spigen,product?lock=256', 'category_slug' => 'accessories', 'brand_name' => 'Spigen'],
            ['name' => 'Ugreen 100W GaN Charger', 'slug' => 'ugreen-100w-gan', 'description' => 'Compact 4-port fast charger for laptops and phones.', 'price' => 1600000, 'stock' => 70, 'image' => 'https://loremflickr.com/600/600/ugreen,product?lock=257', 'category_slug' => 'accessories', 'brand_name' => 'Ugreen'],
            ['name' => 'Apple AirTag 4-Pack', 'slug' => 'apple-airtag-4-pack', 'description' => 'Track your keys, wallet, and luggage.', 'price' => 2800000, 'stock' => 50, 'image' => 'https://loremflickr.com/600/600/apple,product?lock=258', 'category_slug' => 'accessories', 'brand_name' => 'Apple']
        ];

        $this->insertProducts($products);
    }

    private function seedAmazonScrapedProducts(): void
    {
        $csvPath = base_path('amazon-data-cleaning/cleaned_data/products.csv');
        if (!file_exists($csvPath)) {
            Log::warning("Amazon scraped CSV not found at {$csvPath}");
            return;
        }

        if (($handle = fopen($csvPath, 'r')) !== false) {
            $headers = fgetcsv($handle);
            if (!$headers) return;
            
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) !== count($headers)) continue;
                $row = array_combine($headers, $data);

                $categorySlug = $row['category'] ?? 'accessories';
                $brandName = $row['brand'] ?? 'Generic';
                $title = $row['title'] ?? 'Unknown Product';
                $priceUSD = floatval($row['price'] ?? 0);
                $imageUrl = $row['image_url'] ?? '';

                if ($priceUSD == 0) continue;

                $priceVND = round($priceUSD * 25400, -3);
                $brandSlug = Str::slug($brandName);

                $category = Category::firstOrCreate(
                    ['slug' => $categorySlug],
                    ['name' => ucwords(str_replace('-', ' ', $categorySlug)), 'description' => 'Amazon Scraped Category', 'status' => true]
                );

                $brand = Brand::firstOrCreate(
                    ['slug' => $brandSlug],
                    ['name' => $brandName, 'status' => true]
                );

                Product::firstOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'name' => $title,
                        'description' => 'Real Amazon Product Scraping Result.',
                        'price' => $priceVND,
                        'stock' => rand(10, 50),
                        'image' => $imageUrl,
                        'status' => true,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );
            }
            fclose($handle);
        }
    }
}
