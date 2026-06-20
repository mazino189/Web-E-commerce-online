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
        // 1. Clean existing products safely
        Product::query()->delete();

        $this->seedVietnameseElectronics();
    }

    private function seedVietnameseElectronics(): void
    {
        $products = [
            // smartphones-tablets
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'price' => 33990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung',
                'description' => 'Siêu phẩm Galaxy S24 Ultra 512GB với khung viền Titanium bền bỉ, camera 200MP siêu nét và tích hợp Galaxy AI tiên tiến mang đến trải nghiệm đỉnh cao.',
                'image' => 'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925969/images_opl1ry.jpg',
            ],
            [
                'name' => 'Samsung Galaxy Z Fold5 256GB',
                'price' => 38990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung',
                'description' => 'Màn hình gập cực đại 7.6 inch, thiết kế bản lề Flex mỏng nhẹ hơn, hiệu năng siêu mạnh với vi xử lý Snapdragon 8 Gen 2 for Galaxy.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_1_sqggvt.jpg',
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 Ultra 512GB',
                'price' => 26990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Samsung',
                'description' => 'Máy tính bảng cao cấp với màn hình 14.6 inch AMOLED siêu sắc nét, hiệu năng mạnh mẽ từ chip Snapdragon 8 Gen 2, hỗ trợ bút S Pen đa năng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925969/images_2_vmnjbv.jpg',
            ],
            [
                'name' => 'Tablet Apple iPad Pro 12.9 inch M2 512GB',
                'price' => 26990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPad Pro 12.9 inch M2 512GB với màn hình Liquid Retina XDR siêu sáng, hiệu năng đỉnh cao từ chip M2, hỗ trợ Apple Pencil 2 và Magic Keyboard cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_3_gbxome.jpg',
            ],
            [
                'name' => 'Tablet Apple iPad Air 5 256GB',
                'price' => 14990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPad Air 5 256GB với thiết kế mỏng nhẹ, màn hình 10.9 inch Retina sắc nét, hiệu năng mạnh mẽ từ chip M1, hỗ trợ Apple Pencil 2 và Magic Keyboard cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_4_qot4vb.jpg',
            ],
            [
                'name' => 'Tablet Apple ipad pro 2018 11.9 inch 256GB',
                'price' => 14990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPad Pro 2018 11.9 inch 256GB với thiết kế viền mỏng, màn hình Liquid Retina sắc nét, hiệu năng mạnh mẽ từ chip A12X Bionic, hỗ trợ Apple Pencil và Smart Keyboard cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_5_itqg63.jpg',
            ],
            [
                'name' => 'Tablet Huawei MatePad Pro 12.6 inch 256GB',
                'price' => 14990000,    
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Huawei',
                'description' => 'Tablet Huawei MatePad Pro 12.6 inch 256GB với thiết kế hiện đại, màn hình OLED sắc nét, hiệu năng mạnh mẽ từ chip Kirin 9000S, hỗ trợ bút M-Pencil cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_6_w502mg.jpg',
            ],
            [
                'name' => 'Tablet Huawei MatePad 11 inch 128GB',
                'price' => 8990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Huawei',
                'description' => 'Tablet Huawei MatePad 11 inch 128GB với thiết kế mỏng nhẹ, màn hình 11 inch Retina sắc nét, hiệu năng mạnh mẽ từ chip Kirin 9000S, hỗ trợ bút M-Pencil cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_7_igmcgb.jpg',
            ],
            [
                'name' => 'Oneplus Pad 2 pro 12.4 inch 256GB',
                'price' => 14990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Oneplus',
                'description' => 'OnePlus Pad 2 Pro 12.4 inch 256GB với thiết kế sang trọng, màn hình 12.4 inch AMOLED sắc nét, hiệu năng mạnh mẽ từ chip Snapdragon 8 Gen 2, hỗ trợ bút S Pen đa năng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925968/images_8_ri30wd.jpg',
            ],
            [
                'name' => 'Oneplus Pad 3 pro 12.4 inch 256GB',
                'price' => 14990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Oneplus',
                'description' => 'OnePlus Pad 3 Pro 12.4 inch 256GB với thiết kế sang trọng, màn hình 12.4 inch AMOLED sắc nét, hiệu năng mạnh mẽ từ chip Snapdragon 8 Gen 2, hỗ trợ bút S Pen đa năng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925967/images_9_mutllj.jpg',
            ],
            [
                'name' => 'Tablet Xiaomi Pad 5 Pro 12.4 inch 256GB',
                'price' => 14990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Xiaomi',
                'description' => 'Xiaomi Pad 5 Pro 12.4 inch 256GB với thiết kế hiện đại, màn hình 12.4 inch AMOLED sắc nét, hiệu năng mạnh mẽ từ chip Snapdragon 870, hỗ trợ bút stylus cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925967/images_10_mxwbdp.jpg',
            ],
            [
                'name'=> 'Tablet Xiaomi Pad 7 Pro 12.4 inch 128GB',
                'price' => 12990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Xiaomi',
                'description' => 'Xiaomi Pad 7 Pro 12.4 inch 128GB với thiết kế hiện đại, màn hình 12.4 inch AMOLED sắc nét, hiệu năng mạnh mẽ từ chip Snapdragon 870, hỗ trợ bút stylus cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925967/images_11_zpe4ug.jpg',
            ],
            [
                'name' => 'Tablet Xiaomi Pad 8 pro 12.4 inch 256GB',
                'price' => 14990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Xiaomi',
                'description' => 'Xiaomi Pad 8 Pro 12.4 inch 256GB với thiết kế hiện đại, màn hình 12.4 inch AMOLED sắc nét, hiệu năng mạnh mẽ từ chip Snapdragon 870, hỗ trợ bút stylus cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925967/images_12_ne2baa.jpg',
            ],
            [
                'name' => 'Iphone 16 Pro Max 512GB',
                'price' => 33990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPhone 16 Pro Max 512GB với thiết kế sang trọng, màn hình Super Retina XDR siêu sáng, hiệu năng mạnh mẽ từ chip A17 Pro, hệ thống camera Pro nâng cấp với cảm biến lớn hơn và hỗ trợ 5G toàn cầu.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925967/images_13_y9quvq.jpg',    
            ],
            [
                'name' => 'Iphone 16 Pro 256GB',
                'price' => 26990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPhone 16 Pro 256GB với thiết kế sang trọng, màn hình Super Retina XDR siêu sáng, hiệu năng mạnh mẽ từ chip A17 Pro, hệ thống camera Pro nâng cấp với cảm biến lớn hơn và hỗ trợ 5G toàn cầu.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925967/images_14_epsmbo.jpg',
            ],
            [
                'name' => 'Iphone 16 128GB',
                'price' => 19990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPhone 16 128GB với thiết kế sang trọng, màn hình Super Retina XDR siêu sáng, hiệu năng mạnh mẽ từ chip A17 Pro, hệ thống camera nâng cấp với cảm biến lớn hơn và hỗ trợ 5G toàn cầu.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925966/images_15_xbkxui.jpg',
            ],
            [
                'name' => 'Iphone 16 Plus 256GB',
                'price' => 26990000,
                'category_slug' => 'smartphones-tablets',
                'brand_name' => 'Apple',
                'description' => 'iPhone 16 Plus 256GB với thiết kế sang trọng, màn hình Super Retina XDR siêu sáng, hiệu năng mạnh mẽ từ chip A17 Pro, hệ thống camera nâng cấp với cảm biến lớn hơn và hỗ trợ 5G toàn cầu.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781927421/images_89_iynj4q.jpg',
            ],

            // wearables-smartwatches
            [
                'name' => 'Đồng hồ Samsung Galaxy Watch 6 Classic',
                'price' => 8990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Samsung',
                'description' => 'Thiết kế viền xoay vật lý cổ điển, theo dõi sức khỏe toàn diện với công nghệ phân tích giấc ngủ và đo huyết áp chuyên sâu.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925966/images_19_fbl7m4.jpg',    
            ],
            [
                'name' => 'Đồng hồ Apple Watch Series 9',
                'price' => 8990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Apple',
                'description' => 'Apple Watch Series 9 với chip S9 mạnh mẽ hơn 20%, màn hình Retina sáng hơn 30%, theo dõi sức khỏe nâng cao với cảm biến nhiệt độ da và đo oxy trong máu chính xác hơn.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925966/images_18_mwaglz.jpg',
            ],
            [
                'name' => 'Đồng hồ Huawei Watch GT 3 Pro',
                'price' => 6990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Huawei',
                'description' => 'Huawei Watch GT 3 Pro với thiết kế sang trọng từ chất liệu titanium, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện với cảm biến nhịp tim TruSeen 5.0+ và hỗ trợ sạc không dây tiện lợi.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925966/images_18_mwaglz.jpg',
            ],
            [
                'name' => 'Đồng hồ Garmin Venu 2 Plus',
                'price' => 6990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Garmin',
                'description' => 'Garmin Venu 2 Plus với thiết kế thể thao năng động, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện với cảm biến nhịp tim Elevate 4.0 và hỗ trợ gọi điện trực tiếp từ đồng hồ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925966/images_20_woly7i.jpg',
            ],
            [
                'name' => 'Đồng hồ Fitbit Sense 2',
                'price' => 4990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Fitbit',
                'description' => 'Fitbit Sense 2 với thiết kế mỏng nhẹ, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện with cảm biến nhịp tim PurePulse 2.0 and hỗ trợ đo nhiệt độ da để phát hiện sớm các dấu hiệu bệnh tật.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_24_yz0eyz.jpg',
            ],
            [
                'name' => 'Đồng hồ Xiaomi Mi Watch 2 Pro',
                'price' => 2990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Xiaomi',
                'description' => 'Xiaomi Mi Watch 2 Pro with thiết kế hiện đại, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện with cảm biến nhịp tim BioTracker 2 PPG and hỗ trợ GPS chính xác cho các hoạt động ngoài trời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_22_ta4yxd.jpg',        
            ],
            [
                'name' => 'Đồng hồ Realme Watch 3 Pro',
                'price' => 1990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Realme',
                'description' => 'Realme Watch 3 Pro with thiết kế thể thao năng động, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện with cảm biến nhịp tim Realbeat and hỗ trợ GPS tích hợp cho các hoạt động ngoài trời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_25_jagxa4.jpg',
            ],
            [
                'name' => 'Đồng hồ Oppo Watch 2',
                'price' => 3990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Oppo',
                'description' => 'Oppo Watch 2 with thiết kế sang trọng, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện with cảm biến nhịp tim BioTracker 2 PPG and hỗ trợ GPS chính xác cho các hoạt động ngoài trời.',         
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_23_axlbni.jpg',
            ],
            [
                'name' => 'Đồng hồ Amazfit GTR 4',
                'price' => 2990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Amazfit',
                'description' => 'Amazfit GTR 4 with thiết kế cổ điển sang trọng, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện with cảm biến nhịp tim BioTracker 4.0 and hỗ trợ GPS tích hợp cho các hoạt động ngoài trời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_26_v28ncw.jpg',
            ],
            [
                'name' => 'Đồng hồ Honor Watch GS 3',
                'price' => 1990000,
                'category_slug' => 'wearables-smartwatches',
                'brand_name' => 'Honor',
                'description' => 'Honor Watch GS 3 with thiết kế hiện đại, màn hình AMOLED sắc nét, theo dõi sức khỏe toàn diện with cảm biến nhịp tim TruSeen 5.0 and hỗ trợ GPS tích hợp cho các hoạt động ngoài trời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_26_v28ncw.jpg',
            ],

            // laptops-computers
            [
                'name' => 'Laptop Dell XPS 15 9530',
                'price' => 69990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Dell',
                'description' => 'Kiệt tác laptop cao cấp từ Dell với vi xử lý Intel Core i7 thế hệ 13th, màn hình OLED sắc nét, thiết kế vỏ nhôm sang trọng và hiệu suất đồ họa cực kỳ ấn tượng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925965/images_27_algqdx.jpg',
            ],
            [
                'name' => 'Laptop Asus ROG Strix G15',
                'price' => 30990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Asus',
                'description' => 'Sức mạnh chuẩn Gaming với chip Ryzen 7 và card RTX mạnh mẽ. Tần số quét cao 144Hz cho khung hình mượt mà không độ trễ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925964/images_28_gix5ao.jpg',
            ],
            [
                'name' => 'Laptop HP Spectre x360 14',
                'price' => 26990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'HP',
                'description' => 'Laptop 2 trong 1 với thiết kế xoay gập linh hoạt, màn hình OLED sắc nét, hiệu năng mạnh mẽ từ chip Intel Core i7 và thời lượng pin ấn tượng cho cả ngày làm việc.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925964/images_29_wapr0h.jpg',
            ],
            [
                'name' => 'Laptop Lenovo ThinkPad X1 Carbon Gen 10',
                'price' => 39990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Lenovo',
                'description' => 'Laptop doanh nhân cao cấp với thiết kế siung trọng, màn hình 14 inch sắc nét, hiệu năng mạnh mẽ từ chip Intel Core i7 thế hệ 13th và độ bền đạt chuẩn quân đội Mỹ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781927733/images_90_tepxly.jpg',
            ],
            [
                'name' => 'Laptop Apple MacBook Pro 16 inch M2 Pro',
                'price' => 49990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Apple',
                'description' => 'MacBook Pro 16 inch M2 Pro với thiết kế sang trọng, màn hình Retina XDR siêu sáng, hiệu năng mạnh mẽ từ chip M2 Pro, hệ thống tản nhiệt cải tiến và thời lượng pin lên đến 21 giờ cho trải nghiệm làm việc và sáng tạo đỉnh cao.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925964/images_31_xbkubz.jpg',    
            ],
            [
                'name' => 'Laptop Apple MacBook Air 15 inch M2',
                'price' => 29990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Apple',
                'description' => 'MacBook Air 15 inch M2 với thiết kế mỏng nhẹ, màn hình Retina sắc nét, hiệu năng mạnh mẽ từ chip M2, hệ thống tản nhiệt cải tiến và thời lượng pin lên đến 18 giờ cho trải nghiệm làm việc và giải trí tuyệt vời.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925964/images_32_jit8vt.jpg',
            ],
            [
                'name' => 'Laptop Acer Swift 3X',
                'price' => 19990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Acer',
                'description' => 'Laptop mỏng nhẹ với hiệu năng mạnh mẽ từ chip Intel Core i7 và card đồ họa Iris Xe, màn hình Full HD sắc nét, thời lượng pin dài cho cả ngày làm việc và học tập.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925964/download_ogbayj.webp',
            ],
            [
                'name' => 'Laptop MSI Prestige 14 Evo',
                'price' => 24990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'MSI',
                'description' => 'Laptop cao cấp cho sáng tạo nội dung với chip Intel Core i7 thế hệ 13th, màn hình 14 inch 4K sắc nét, card đồ họa NVIDIA GeForce MX450 và thiết kế vỏ nhôm sang trọng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_33_g2uypb.jpg',
            ],
            [
                'name' => 'Laptop Razer Blade 15 Advanced',
                'price' => 39990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Razer',
                'description' => 'Laptop gaming cao cấp với thiết kế mỏng nhẹ, màn hình 15.6 inch 4K OLED sắc nét, hiệu năng mạnh mẽ từ chip Intel Core i7 và card đồ họa NVIDIA GeForce RTX 3070 Ti cho trải nghiệm chơi game đỉnh cao.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_34_yc6xav.jpg',
            ],
            [
                'name' => 'Laptop Microsoft Surface Laptop 5',
                'price' => 29990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Microsoft',
                'description' => 'Laptop cao cấp với thiết kế sang trọng, màn hình PixelSense sắc nét, hiệu năng mạnh mẽ từ chip Intel Core i7 thế hệ 13th và thời lượng pin dài cho cả ngày làm việc và học tập.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_35_ilmuah.jpg',
            ],
            [
                'name' => 'Laptop ROG Flow Z13 2023',
                'price' => 39990000,
                'category_slug' => 'laptops-computers',
                'brand_name' => 'Asus',
                'description' => 'Laptop gaming 2 trong 1 with thiết kế xoay gập linh hoạt, màn hình 13.4 inch 4K OLED sắc nét, hiệu năng mạnh mẽ từ chip Intel Core i9 and card đồ họa NVIDIA GeForce RTX 3050 Ti cho trải nghiệm chơi game đỉnh cao.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_36_ad86so.jpg',
            ],

            // peripherals & accessories
            [
                'name' => 'Chuột không dây Logitech MX Master 3S',
                'price' => 2490000,
                'category_slug' => 'accessories',
                'brand_name' => 'Logitech',
                'description' => 'Chuột công thái học đỉnh cao của Logitech, cảm biến 8000 DPI siêu nhạy trên mọi mặt phẳng kể cả kính, cuộn MagSpeed siêu tốc siêu êm.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_37_tlqckl.jpg',
            ],
            [
                'name' => 'Bàn phím cơ Razer BlackWidow V3 Pro',
                'price' => 4990000,
                'category_slug' => 'accessories',
                'brand_name' => 'Razer',
                'description' => 'Bàn phím cơ không dây cao cấp with switch Razer Green clicky, đèn RGB Chroma sống động, kết nối đa dạng (Bluetooth, 2.4GHz, USB-C).',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_38_sgpqbw.jpg',
            ],
            [
                'name' => 'Chuột chơi game SteelSeries Rival 600',
                'price' => 1990000,
                'category_slug' => 'accessories',
                'brand_name' => 'SteelSeries',
                'description' => 'Chuột chơi game chuyên nghiệp with cảm biến TrueMove3+ siêu chính xác, thiết kế cân bằng trọng lượng and đèn RGB PrismSync sống động.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925963/images_39_t9qelk.jpg',
            ],
            [
                'name' => 'Bàn phím cơ Corsair K95 RGB Platinum XT',
                'price' => 3990000,
                'category_slug' => 'accessories',
                'brand_name' => 'Corsair',
                'description' => 'Bàn phím cơ cao cấp with switch Cherry MX Speed, đèn RGB sống động, bộ nhớ onboard lưu trữ macro and kết nối USB pass-through tiện lợi.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925962/images_40_uinun1.jpg',
            ],
            [
                'name' => 'Chuột không dây Razer DeathAdder V2 Pro',
                'price' => 2990000,
                'category_slug' => 'accessories',
                'brand_name' => 'Razer',
                'description' => 'Chuột chơi game không dây cao cấp with cảm biến Focus+ 20K DPI siêu nhạy, thiết kế công thái học and đèn RGB Chroma sống động.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925962/images_41_gfxagu.jpg',
            ],
            [
                'name' => 'Bàn phím cơ SteelSeries Apex Pro TKL',
                'price' => 4990000,
                'category_slug' => 'accessories',
                'brand_name' => 'SteelSeries',
                'description' => 'Bàn phím cơ cao cấp with switch OmniPoint điều chỉnh lực nhấn, đèn RGB PrismSync sống động and thiết kế TKL gọn nhẹ tiện lợi.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925962/images_42_v4uuil.jpg',
            ],
            [
                'name' => 'Chuột chơi game Logitech G502 Lightspeed',
                'price' => 2990000, 
                'category_slug' => 'accessories',
                'brand_name' => 'Logitech',
                'description' => 'Chuột chơi game không dây cao cấp with cảm biến HERO 25K siêu chính xác, thiết kế công thái học and đèn RGB Lightsync sống động.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925962/images_43_ltxl7x.jpg',
            ],
            [
                'name' => 'Bàn phím cơ Razer Huntsman Elite',
                'price' => 3990000,
                'category_slug' => 'accessories',
                'brand_name' => 'Razer',
                'description' => 'Bàn phím cơ cao cấp with switch Razer Opto-Mechanical, đèn RGB Chroma sống động, bộ nhớ onboard lưu trữ macro and kết nối USB pass-through tiện lợi.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925962/images_44_wuz35i.jpg',
            ],
            [
                'name' => 'Sạc dự phòng Anker PowerCore 26800mAh',
                'price' => 1290000,
                'category_slug' => 'accessories',
                'brand_name' => 'Anker',
                'description' => 'Sạc dự phòng dung lượng lớn 26800mAh with công nghệ PowerIQ 3.0 sạc nhanh cho nhiều thiết bị, thiết kế gọn nhẹ tiện lợi and bảo vệ an toàn cho pin.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925961/images_45_qfukyr.jpg',
            ],
            [
                'name' => 'Sạc dự phòng Xiaomi Mi Power Bank 3 Pro 20000mAh',
                'price' => 899000,
                'category_slug' => 'accessories',
                'brand_name' => 'Xiaomi',
                'description' => 'Sạc dự phòng dung lượng lớn 20000mAh with công nghệ sạc nhanh 45W, thiết kế gọn nhẹ tiện lợi and bảo vệ an toàn cho pin.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925961/images_zzjvnt.png',    
            ],

            // audio-speakers
            [
                'name' => 'Tai nghe AirPods Pro Gen 2 (Type-C)',
                'price' => 6190000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Apple',
                'description' => 'Tai nghe True Wireless cao cấp của Apple nay đã hỗ trợ cổng sạc Type-C. Chống ồn chủ động (ANC) tốt hơn gấp 2 lần.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925961/images_46_oj7vge.jpg',
            ],
            [
                'name' => 'Loa Bluetooth Marshall Stanmore 3',
                'price' => 10990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Marshall',
                'description' => 'Loa để bàn công suất mạnh mẽ mang đậm phong cách vintage của Marshall, kết nối Bluetooth 5.2 tiên tiến, âm thanh sống động lấp đầy không gian lớn.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925961/images_47_b7kqmz.jpg',
            ],
            [
                'name' => 'Tai nghe Sony WH-1000XM5',
                'price' => 8990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Sony',
                'description' => 'Tai nghe chụp tai cao cấp with chống ồn chủ động (ANC) hàng đầu, âm thanh chất lượng cao, thời lượng pin lên đến 30 giờ and thiết kế nhẹ nhàng thoải mái.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925961/images_48_t0nfvh.jpg',
            ],
            [
                'name' => 'Loa Bluetooth Bose SoundLink Revolve+ II',
                'price' => 7990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Bose',
                'description' => 'Loa di động công suất mạnh mẽ with âm thanh 360 độ sống động, chống nước IPX4, kết nối Bluetooth ổn định and thời lượng pin lên đến 17 giờ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925961/images_49_vkz6cm.jpg',
            ],
            [
                'name' => 'Tai nghe Bose QuietComfort 45',
                'price' => 6990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Bose',
                'description' => 'Tai nghe chụp tai cao cấp with chống ồn chủ động (ANC) hàng đầu, âm thanh chất lượng cao, thời lượng pin lên đến 24 giờ and thiết kế nhẹ nhàng thoải mái.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_52_nag01a.jpg',
            ],
            [
                'name' => 'Loa Bluetooth JBL Charge 5',
                'price' => 3990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'JBL',
                'description' => 'Loa di động công suất mạnh mẽ with âm thanh sống động, chống nước IP67, kết nối Bluetooth ổn định and thời lượng pin lên đến 20 giờ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_50_smwrfg.jpg',
            ],
            [
                'name' => 'Tai nghe Sony WF-1000XM4',
                'price' => 5990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Sony',
                'description' => 'Tai nghe True Wireless cao cấp with chống ồn chủ động (ANC) hàng đầu, âm thanh chất lượng cao, thời lượng pin lên đến 8 giờ and thiết kế nhỏ gọn thoải mái.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_51_iwgw7e.jpg',
            ],
            [
                'name' => 'Loa Bluetooth Anker Soundcore Motion+',
                'price' => 2990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Anker',
                'description' => 'Loa di động công suất mạnh mẽ with âm thanh sống động, chống nước IPX7, kết nối Bluetooth ổn định and thời lượng pin lên đến 12 giờ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_53_knzvoh.jpg',
            ],
            [
                'name' => 'Tai nghe Sennheiser Momentum True Wireless 3',
                'price' => 6990000,
                'category_slug' => 'audio-speakers',
                'brand_name' => 'Sennheiser',
                'description' => 'Tai nghe True Wireless cao cấp with chống ồn chủ động (ANC) hàng đầu, âm thanh chất lượng cao, thời lượng pin lên đến 7 giờ and thiết kế nhỏ gọn thoải mái.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_54_dncevi.jpg',
            ],

            // cameras-photography
            [
                'name' => 'Máy ảnh Sony Alpha 7 IV',
                'price' => 49990000,
                'category_slug' => 'cameras-photography',
                'brand_name' => 'Sony',
                'description' => 'Máy ảnh mirrorless full-frame with cảm biến 33MP, quay video 4K 60fps, hệ thống lấy nét tự động Fast Hybrid AF and khả năng chụp liên tiếp 10fps.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_55_jk5efn.jpg',
            ],
            [
                'name' => 'Máy ảnh Canon EOS R6 Mark II',
                'price' => 69990000,
                'category_slug' => 'cameras-photography',
                'brand_name' => 'Canon',
                'description' => 'Máy ảnh mirrorless full-frame with cảm biến 20MP, quay video 4K 60fps, hệ thống lấy nét tự động Dual Pixel CMOS AF II and khả năng chụp liên tiếp 12fps.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_56_cw9v46.jpg',
            ],
            [
                'name' => 'Máy ảnh Nikon Z9',
                'price' => 99990000,
                'category_slug' => 'cameras-photography',
                'brand_name' => 'Nikon',
                'description' => 'Máy ảnh mirrorless full-frame with cảm biến 45.7MP, quay video 8K 30fps, hệ thống lấy nét tự động Multi-CAM 20K and khả năng chụp liên tiếp 20fps.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925959/images_57_p1frjr.jpg',
            ],
            [
                'name' => 'Máy ảnh Fujifilm X-T4',
                'price' => 29990000,
                'category_slug' => 'cameras-photography',
                'brand_name' => 'Fujifilm',
                'description' => 'Máy ảnh mirrorless APS-C with cảm biến 26.1MP, quay video 4K 60fps, hệ thống lấy nét tự động Hybrid AF and khả năng chụp liên tiếp 15fps.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925960/images_58_oqjhm4.jpg',
            ],
            [
                'name' => 'Máy ảnh Panasonic Lumix GH5',
                'price' => 24990000,
                'category_slug' => 'cameras-photography',
                'brand_name' => 'Panasonic',
                'description' => 'Máy ảnh mirrorless Micro Four Thirds with cảm biến 20.3MP, quay video 4K 60fps, hệ thống lấy nét tự động DFD and khả năng chụp liên tiếp 12fps.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925959/shopping_vd9oc7.webp',
            ],
            [
                'name' => 'Máy ảnh Olympus OM-D E-M1 Mark III',
                'price' => 19990000,
                'category_slug' => 'cameras-photography',
                'brand_name' => 'Olympus',
                'description' => 'Máy ảnh mirrorless Micro Four Thirds with cảm biến 20.4MP, quay video 4K 30fps, hệ thống lấy nét tự động FAST and khả năng chụp liên tiếp 18fps.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925959/images_59_qrq3tb.jpg',
            ],

            // gaming-gear
            [
                'name' => 'Play Station 5 Console',
                'price' => 13990000,
                'category_slug' => 'gaming-gear',
                'brand_name' => 'Sony',
                'description' => 'Máy chơi game console thế hệ mới with hiệu năng mạnh mẽ từ chip AMD Ryzen Zen 2, đồ họa RDNA 2, hỗ trợ ray tracing and thời gian tải cực nhanh nhờ ổ SSD NVMe.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925959/images_60_e6cwn5.jpg',
            ],
            [
                'name' => 'Xbox Series X Console',
                'price' => 13990000,
                'category_slug' => 'gaming-gear',
                'brand_name' => 'Microsoft',
                'description' => 'Máy chơi game console thế hệ mới with hiệu năng mạnh mẽ từ chip AMD Ryzen Zen 2, đồ họa RDNA 2, hỗ trợ ray tracing and thời gian tải cực nhanh nhờ ổ SSD NVMe.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925958/images_67_ojfxus.jpg',
            ],
            [
                'name' => 'Nintendo Switch OLED Model',
                'price' => 8990000,
                'category_slug' => 'gaming-gear',
                'brand_name' => 'Nintendo',
                'description' => 'Máy chơi game console cầm tay with màn hình OLED 7 inch sắc nét, hiệu năng mạnh mẽ từ chip NVIDIA Custom Tegra, hỗ trợ chơi game đa nền tảng and thời lượng pin lên đến 9 giờ.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781928172/images_91_ecvheu.jpg',
            ],

            // kitchen-appliances
            [
                'name' => 'Máy xay sinh tố Philips HR3556/00',
                'price' => 1990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Philips',
                'description' => 'Máy xay sinh tố công suất 1400W with lưỡi dao ProBlend 6, cối xay dung tích 2L, chế độ xay đa dạng and dễ dàng vệ sinh.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925958/images_68_utetpx.jpg',
            ],
            [
                'name' => 'Nồi chiên không dầu Tefal ActiFry Genius XL',
                'price' => 4990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Tefal',
                'description' => 'Nồi chiên không dầu dung tích 1.7kg with công nghệ ActiFry, điều khiển thông minh, chế độ nấu đa dạng and dễ dàng vệ sinh.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781928351/images_93_s5thin.jpg',
            ],
            [
                'name' => 'Máy pha cà phê DeLonghi Magnifica S',
                'price' => 8990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'DeLonghi',
                'description' => 'Máy pha cà phê tự động with hệ thống xay hạt, áp suất 15 bar, chế độ cappuccino and latte, dễ dàng vệ sinh and bảo trì.',
                'image' =>'',
            ],
            [
                'name' => 'Lò vi sóng Samsung MS23K3513AK',
                'price' => 2990000, 
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Samsung',
                'description' => 'Lò vi sóng dung tích 23L with công nghệ Inverter, chế độ nấu đa dạng, dễ dàng vệ sinh and tiết kiệm điện năng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925957/images_73_sifypp.jpg',
            ],
            [
                'name' => 'Máy rửa chén Bosch SMS46MI05E',
                'price' => 14990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Bosch',
                'description' => 'Máy rửa chén âm tủ with dung tích 13 bộ, công nghệ EcoSilence Drive, chế độ rửa đa dạng, dễ dàng vệ sinh and tiết kiệm điện năng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781928458/images_94_gsyvjf.jpg',
            ],
            [
                'name' => 'Nồi áp suất điện tử Instant Pot Duo 7-in-1',
                'price' => 3990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Instant Pot',
                'description' => 'Nồi áp suất điện tử đa năng with dung tích 6L, 7 chế độ nấu, dễ dàng vệ sinh and tiết kiệm thời gian nấu ăn.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925957/download_qb2vcy.jpg',    
            ],
            [
                'name' => 'Máy làm sữa hạt Joyoung DJ13U-D08D',
                'price' => 2990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Joyoung',
                'description' => 'Máy làm sữa hạt công suất 1000W with lưỡi dao thép không gỉ, dung tích 1.3L, chế độ nấu đa dạng and dễ dàng vệ sinh.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925957/images_72_ywntmb.jpg',
            ],
            [
                'name' => 'Lò nướng điện tử Electrolux EOT3805K',
                'price' => 3990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Electrolux',
                'description' => 'Lò nướng điện tử dung tích 38L with công nghệ AirFry, chế độ nướng đa dạng, dễ dàng vệ sinh and tiết kiệm điện năng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781928678/images_95_hukloa.jpg',
            ],
            [
                'name' => 'Máy làm kem Cuisinart ICE-100',
                'price' => 2990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Cuisinart',
                'description' => 'Máy làm kem công suất 180W with dung tích 1.5L, chế độ làm kem nhanh, dễ dàng vệ sinh and tiết kiệm thời gian.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925957/shopping_1_paqlow.webp',
            ],
            [
                'name' => 'Máy xay cà phê Baratza Encore',
                'price' => 3990000,
                'category_slug' => 'kitchen-appliances',
                'brand_name' => 'Baratza',
                'description' => 'Máy xay cà phê burr grinder with 40 mức điều chỉnh độ mịn, lưỡi dao thép không gỉ, dễ dàng vệ sinh and bảo trì.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925957/download_1_sfvfgd.webp',
            ],

            // cookware
            [
                'name' => 'Bộ nồi chống dính Cuisinart',
                'price' => 4990000,
                'category_slug' => 'cookware',
                'brand_name' => 'Cuisinart',
                'description' => 'Bộ nồi chống dính 10 món with chất liệu thép không gỉ, lớp chống dính an toàn, tay cầm cách nhiệt and dễ dàng vệ sinh.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925956/images_78_iw7ytp.jpg',
            ],
            [
                'name' => 'Chảo chống dính Tefal Expertise',
                'price' => 1290000,
                'category_slug' => 'cookware',
                'brand_name' => 'Tefal',
                'description' => 'Chảo chống dính đường kính 28cm with lớp chống dính Titanium, tay cầm cách nhiệt, dễ dàng vệ sinh and sử dụng trên mọi loại bếp.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925956/download_2_vyspuf.webp',
            ],
            [
                'name' => 'Bộ dao nhà bếp Wüsthof Classic',
                'price' => 8990000,
                'category_slug' => 'cookware',
                'brand_name' => 'Wüsthof',
                'description' => 'Bộ dao nhà bếp 7 món with lưỡi dao thép không gỉ, tay cầm bằng nhựa tổng hợp, thiết kế cân bằng and dễ dàng bảo quản.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/images_79_p5rtdr.jpg',
            ],
            [
                'name' => 'Nồi áp suất Fissler Vitaquick',
                'price' => 4990000,
                'category_slug' => 'cookware',
                'brand_name' => 'Fissler',
                'description' => 'Nồi áp suất dung tích 6L with công nghệ an toàn, van xả áp thông minh, dễ dàng vệ sinh and tiết kiệm thời gian nấu ăn.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/images_85_b6qo0e.jpg',
            ],
            [
                'name' => 'Máy làm bánh mì Panasonic SD-ZP2000KXE',
                'price' => 6990000,
                'category_slug' => 'cookware',
                'brand_name' => 'Panasonic',
                'description' => 'Máy làm bánh mì tự động with công suất 550W, chế độ nướng đa dạng, dễ dàng vệ sinh and bảo trì.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781928678/images_95_hukloa.jpg',
            ],
            [
                'name' => 'Bộ nồi inox WMF ProfiResist',
                'price' => 8990000,
                'category_slug' => 'cookware',
                'brand_name' => 'WMF',
                'description' => 'Bộ nồi inox 5 món with chất liệu thép không gỉ cao cấp, tay cầm cách nhiệt, dễ dàng vệ sinh and sử dụng trên mọi loại bếp.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/images_82_jqqd8p.jpg',
            ],
            [
                'name' => 'Chảo gang Lodge',
                'price' => 1290000,
                'category_slug' => 'cookware',
                'brand_name' => 'Lodge',
                'description' => 'Chảo gang đúc nguyên khối with khả năng giữ nhiệt tốt, bề mặt chống dính tự nhiên, dễ dàng sử dụng trên mọi loại bếp and bền bỉ theo thời gian.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925956/download_2_vyspuf.webp',
            ],

            // tableware
            [
                'name' => 'Bộ bát đĩa sứ Luminarc',
                'price' => 499000,
                'category_slug' => 'tableware',
                'brand_name' => 'Luminarc',
                'description' => 'Bộ bát đĩa sứ 16 món with thiết kế hiện đại, chất liệu sứ cao cấp, dễ dàng vệ sinh and an toàn cho sức khỏe.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925956/download_3_cr6lap.webp',
            ],
            [
                'name' => 'Bộ ly thủy tinh Duralex Picardie',
                'price' => 299000,
                'category_slug' => 'tableware',
                'brand_name' => 'Duralex',
                'description' => 'Bộ ly thủy tinh 6 chiếc with thiết kế cổ điển, chất liệu thủy tinh chịu lực, dễ dàng vệ sinh and an toàn cho sức khỏe.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925956/download_4_kqpjvq.webp',
            ],
            [
                'name' => 'Bộ dao kéo inox Zwilling J.A. Henckels',
                'price' => 8990000,
                'category_slug' => 'tableware',
                'brand_name' => 'Zwilling J.A. Henckels',
                'description' => 'Bộ dao kéo inox 24 món with chất liệu thép không gỉ cao cấp, tay cầm bằng nhựa tổng hợp, thiết kế cân bằng and dễ dàng bảo quản.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781928958/images_97_yh7ebw.jpg',
            ],
            [
                'name' => 'Bộ nồi đất nung Staub',
                'price' => 4990000,
                'category_slug' => 'tableware',
                'brand_name' => 'Staub',
                'description' => 'Bộ nồi đất nung 3 món with chất liệu đất nung cao cấp, lớp men chống dính tự nhiên, dễ dàng sử dụng trên mọi loại bếp and bền bỉ theo thời gian.',              
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/shopping_3_lofnvk.webp',
            ],
            [
                'name' => 'Bộ bát đĩa gốm sứ Villeroy & Boch',
                'price' => 8990000,
                'category_slug' => 'tableware',
                'brand_name' => 'Villeroy & Boch',
                'description' => 'Bộ bát đĩa gốm sứ 18 món with thiết kế sang trọng, chất liệu gốm sứ cao cấp, dễ dàng vệ sinh and an toàn cho sức khỏe.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/images_83_bqnmnn.jpg',
            ],
            [
                'name' => 'Bộ ly rượu vang Riedel',
                'price' => 1299000,
                'category_slug' => 'tableware',
                'brand_name' => 'Riedel',
                'description' => 'Bộ ly rượu vang 4 chiếc with thiết kế tinh tế, chất liệu thủy tinh cao cấp, dễ dàng vệ sinh and nâng cao trải nghiệm thưởng thức rượu.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925954/images_84_w2esno.jpg',
            ],
            [
                'name' => 'Bộ nồi inox Fissler Original-Profi Collection',
                'price' => 14990000,
                'category_slug' => 'tableware',
                'brand_name' => 'Fissler',
                'description' => 'Bộ nồi inox 5 món with chất liệu thép không gỉ cao cấp, tay cầm cách nhiệt, dễ dàng vệ sinh và sử dụng trên mọi loại bếp.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781929096/images_98_ivzncu.jpg',
            ],
            [
                'name' => 'Bộ chén đĩa gốm sứ Le Creuset',
                'price' => 8990000,
                'category_slug' => 'tableware',
                'brand_name' => 'Le Creuset',
                'description' => 'Bộ chén đĩa gốm sứ 16 món with thiết kế hiện đại, chất liệu gốm sứ cao cấp, dễ dàng vệ sinh and an toàn cho sức khỏe.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/images_80_hkbelc.jpg'
            ],
            [
                'name' => 'Bộ ly thủy tinh Schott Zwiesel',
                'price' => 1299000,
                'category_slug' => 'tableware',
                'brand_name' => 'Schott Zwiesel',
                'description' => 'Bộ ly thủy tinh 6 chiếc with thiết kế tinh tế, chất liệu thủy tinh cao cấp, dễ dàng vệ sinh and nâng cao trải nghiệm thưởng thức đồ uống.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925956/download_4_kqpjvq.webp',
            ],
            [
                'name' => 'Bộ nồi áp suất điện tử Instant Pot Duo Evo Plus',
                'price' => 4990000,
                'category_slug' => 'tableware',
                'brand_name' => 'Instant Pot',
                'description' => 'Bộ nồi áp suất điện tử đa năng with dung tích 6L, 10 chế độ nấu, dễ dàng vệ sinh and tiết kiệm thời gian nấu ăn.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925955/images_85_b6qo0e.jpg',
            ],

            // baking-tools
            [
                'name' => 'Khuôn nướng bánh Wilton',
                'price' => 299000,
                'category_slug' => 'baking-tools',
                'brand_name' => 'Wilton',
                'description' => 'Khuôn nướng bánh chống dính with chất liệu thép carbon, thiết kế đa dạng, dễ dàng vệ sinh and sử dụng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925954/shopping_4_vmusjn.webp',
            ],
            [
                'name' => 'Bộ dụng cụ làm bánh KitchenAid',
                'price' => 8990000,
                'category_slug' => 'baking-tools',
                'brand_name' => 'KitchenAid',
                'description' => 'Bộ dụng cụ làm bánh đa năng with chất liệu thép không gỉ, thiết kế tiện lợi, dễ dàng vệ sinh and bảo quản.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925954/images_86_vnilhu.jpg',    
            ],
            [
                'name' => 'Máy đánh trứng Cuisinart HM-90S',
                'price' => 1990000,
                'category_slug' => 'baking-tools',
                'brand_name' => 'Cuisinart',
                'description' => 'Máy đánh trứng công suất 220W with 9 tốc độ, que đánh thép không gỉ, dễ dàng vệ sinh and bảo quản.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925954/shopping_4_vmusjn.webp',
            ],
            [
                'name' => 'Bộ khuôn bánh cupcake USA Pan',
                'price' => 399000,
                'category_slug' => 'baking-tools',
                'brand_name' => 'USA Pan',
                'description' => 'Bộ khuôn bánh cupcake chống dính with chất liệu thép carbon, thiết kế đa dạng, dễ dàng vệ sinh and sử dụng.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781925954/images_88_sglbrq.jpg',
            ],

            // food-storage
            [
                'name' => 'Hộp đựng thực phẩm Lock&Lock',
                'price' => 299000,
                'category_slug' => 'food-storage',
                'brand_name' => 'Lock&Lock',
                'description' => 'Hộp đựng thực phẩm chống thấm with chất liệu nhựa PP cao cấp, thiết kế kín đáo, dễ dàng vệ sinh and bảo quản thực phẩm.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781929316/images_99_mi0ku0.jpg',
            ],
            [
                'name' => 'Bộ hộp đựng thực phẩm Glasslock',
                'price' => 499000,
                'category_slug' => 'food-storage',
                'brand_name' => 'Glasslock',
                'description' => 'Bộ hộp đựng thực phẩm bằng thủy tinh with nắp nhựa PP, thiết kế kín đáo, dễ dàng vệ sinh and bảo quản thực phẩm an toàn.',
                'image' =>'https://res.cloudinary.com/dli2clp3p/image/upload/v1781929369/images_100_gqqaxb.jpg',
            ],
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

    private function verifyAndAuthenticateImage(?string $url, string $categorySlug): string
    {
        if (empty($url)) {
            return $this->getFallbackImage($categorySlug);
        }

        try {
            $response = Http::timeout(3)->retry(3, 100)->head($url);
            if ($response->status() === 200 && str_contains(strtolower($response->header('Content-Type', '')), 'image')) {
                return $url;
            }
            Log::warning("Image authentication failed for {$url} (Status: {$response->status()}). Falling back.");
        } catch (\Exception $e) {
            Log::warning("Image authentication error for {$url}: " . $e->getMessage() . ". Falling back.");
        }

        return $this->getFallbackImage($categorySlug);
    }

    private function getFallbackImage(string $categorySlug): string
    {
        $fallbacks = [
            'smartphones-tablets' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80&auto=format',
            'laptops-computers' => 'https://images.unsplash.com/photo-1496181130204-755241544e35?w=600&q=80&auto=format',
            'accessories' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&q=80&auto=format',
            'audio-speakers' => 'https://images.unsplash.com/photo-1543510473-ac2c35329a28?w=600&q=80&auto=format',
            'wearables-smartwatches' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600&q=80&auto=format',
            'peripherals' => 'https://images.unsplash.com/photo-1527814050087-37938154799f?w=600&q=80&auto=format',
            'cameras-photography' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&q=80&auto=format',
            'gaming-gear' => 'https://images.unsplash.com/photo-1600080972464-8e5f358024af?w=600&q=80&auto=format',
            'kitchen-appliances' => 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=600&q=80&auto=format',
            'cookware' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=600&q=80&auto=format',
            'tableware' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600&q=80&auto=format',
            'baking-tools' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80&auto=format',
            'food-storage' => 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=600&q=80&auto=format'
        ];

        return $fallbacks[$categorySlug] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80&auto=format';
    }
}