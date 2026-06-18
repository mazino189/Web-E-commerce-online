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

        $this->seedVietnameseElectronics();
    }

    private function seedVietnameseElectronics(): void
    {
        $products = [
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'price' => 33990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung',
                'description' => 'Siêu phẩm Galaxy S24 Ultra 512GB với khung viền Titanium bền bỉ, camera 200MP siêu nét và tích hợp Galaxy AI tiên tiến mang đến trải nghiệm đỉnh cao.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/42/307174/samsung-galaxy-s24-ultra-grey-thumb-600x600.jpg',
            ],
            [
                'name' => 'Samsung Galaxy Z Fold5 256GB',
                'price' => 38990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung',
                'description' => 'Màn hình gập cực đại 7.6 inch, thiết kế bản lề Flex mỏng nhẹ hơn, hiệu năng siêu mạnh với vi xử lý Snapdragon 8 Gen 2 for Galaxy.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/42/301608/samsung-galaxy-z-fold5-blue-thumb-600x600.jpg',
            ],
            [
                'name' => 'Đồng hồ Samsung Galaxy Watch 6 Classic',
                'price' => 8990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Samsung',
                'description' => 'Thiết kế viền xoay vật lý cổ điển, theo dõi sức khỏe toàn diện với công nghệ phân tích giấc ngủ và đo huyết áp chuyên sâu.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/7077/309325/samsung-galaxy-watch6-classic-bluetooth-43mm-den-thumb-600x600.jpg',
            ],
            [
                'name' => 'Laptop Dell XPS 15 9530',
                'price' => 69990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Dell',
                'description' => 'Kiệt tác laptop cao cấp từ Dell với vi xử lý Intel Core i7 thế hệ 13th, màn hình OLED sắc nét, thiết kế vỏ nhôm sang trọng và hiệu suất đồ họa cực kỳ ấn tượng.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/44/306713/dell-xps-15-9530-i7-71014801-thumb-600x600.jpg',
            ],
            [
                'name' => 'Laptop Asus ROG Strix G15',
                'price' => 30990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Asus',
                'description' => 'Sức mạnh chuẩn Gaming với chip Ryzen 7 và card RTX mạnh mẽ. Tần số quét cao 144Hz cho khung hình mượt mà không độ trễ.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/44/282956/asus-rog-strix-gaming-g513ih-r7-hn015w-thumb-600x600.jpg',
            ],
            [
                'name' => 'Chuột không dây Logitech MX Master 3S',
                'price' => 2490000,
                'category_slug' => 'peripherals',
                'brand_name' => 'Logitech',
                'description' => 'Chuột công thái học đỉnh cao của Logitech, cảm biến 8000 DPI siêu nhạy trên mọi mặt phẳng kể cả kính, cuộn MagSpeed siêu tốc siêu êm.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/86/299185/chuot-bluetooth-logitech-mx-master-3s-den-thumb-600x600.jpg',
            ],
            [
                'name' => 'Tai nghe AirPods Pro Gen 2 (Type-C)',
                'price' => 6190000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Apple',
                'description' => 'Tai nghe True Wireless cao cấp của Apple nay đã hỗ trợ cổng sạc Type-C. Chống ồn chủ động (ANC) tốt hơn gấp 2 lần.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/54/314207/tai-nghe-bluetooth-airpods-pro-gen-2-magsafe-type-c-apple-thumb-600x600.jpg',
            ],
            [
                'name' => 'Loa Bluetooth Marshall Stanmore 3',
                'price' => 10990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Marshall',
                'description' => 'Loa để bàn công suất mạnh mẽ mang đậm phong cách vintage của Marshall, kết nối Bluetooth 5.2 tiên tiến, âm thanh sống động lấp đầy không gian lớn.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/2162/304380/loa-bluetooth-marshall-stanmore-iii-thumb-600x600.jpg',
            ],
            [
                'name' => 'Đồng hồ Garmin Fenix 7 Pro',
                'price' => 22990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Garmin',
                'description' => 'Siêu phẩm đồng hồ thể thao chuyên nghiệp với thấu kính sạc năng lượng mặt trời (Solar), theo dõi thể lực nâng cao và định vị GPS đa băng tần.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/7077/308157/garmin-fenix-7-pro-solar-titanium-den-thumb-600x600.jpg',
            ],
            [
                'name' => 'Bàn phím cơ Akko 3098B Multi-modes',
                'price' => 2190000,
                'category_slug' => 'peripherals',
                'brand_name' => 'Akko',
                'description' => 'Bàn phím cơ đa kết nối (Bluetooth 5.0, 2.4Ghz, Type-C) với keycap PBT chất lượng, thiết kế 98 phím tinh gọn nhưng đầy đủ chức năng.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/86/298810/ban-phim-co-khong-day-akko-3098b-multi-modes-black-gold-thumb-1-600x600.jpg',
            ],
            [
                'name' => 'Sạc dự phòng Anker PowerCore 20000mAh',
                'price' => 1290000,
                'category_slug' => 'accessories',
                'brand_name' => 'Anker',
                'description' => 'Dung lượng khủng 20.000mAh, hỗ trợ sạc nhanh 20W với công nghệ bảo vệ đa lớp an toàn tuyệt đối từ Anker.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/79/302826/pin-sac-du-phong-polymer-20000mah-20w-type-c-anker-a1367-thumb-1-600x600.jpg',
            ],
            [
                'name' => 'Màn hình LG 27UP850N-W 27 inch 4K',
                'price' => 9590000,
                'category_slug' => 'peripherals',
                'brand_name' => 'LG',
                'description' => 'Màn hình độ phân giải 4K UHD cực nét với tấm nền IPS, hỗ trợ VESA DisplayHDR 400 và chuẩn màu DCI-P3 95% hoàn hảo cho đồ họa.',
                'image' => 'https://cdn.tgdd.vn/Products/Images/5697/300262/man-hinh-lg-27up850n-w-27-inch-4k-thumb-600x600.jpg',
            ]
        ];

        foreach ($products as $item) {
            $category = Category::firstOrCreate(
                ['slug' => $item['category_slug']],
                ['name' => ucwords(str_replace('-', ' ', $item['category_slug'])), 'description' => 'Danh mục sản phẩm', 'status' => true]
            );

            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($item['brand_name'])],
                ['name' => $item['brand_name'], 'status' => true]
            );

            $authenticatedImage = $this->verifyAndAuthenticateImage($item['image'], $item['category_slug']);
            
            Product::firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => rand(10, 50),
                    'image' => $authenticatedImage,
                    'status' => true,
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                ]
            );
        }
    }

    private function verifyAndAuthenticateImage(string $url, string $categorySlug): string
    {
        try {
            $response = Http::timeout(2)->head($url);
            if ($response->status() === 200 && str_contains(strtolower($response->header('Content-Type', '')), 'image')) {
                return $url;
            }
            Log::warning("Image authentication failed for {$url} (Status: {$response->status()}). Falling back.");
        } catch (\Exception $e) {
            Log::warning("Image authentication error for {$url}: " . $e->getMessage() . ". Falling back.");
        }

        $fallbacks = [
            'smartphones-tablets' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80&auto=format',
            'laptops-computers' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&q=80&auto=format',
            'accessories' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=600&q=80&auto=format',
            'audio-speakers' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=600&q=80&auto=format',
            'wearables-smartwatches' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&q=80&auto=format',
            'peripherals' => 'https://images.unsplash.com/photo-1527814050087-37938154799f?w=600&q=80&auto=format',
        ];

        return $fallbacks[$categorySlug] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80&auto=format';
    }
}
