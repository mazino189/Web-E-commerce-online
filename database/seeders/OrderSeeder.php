<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Tạo 15 đơn hàng lịch sử
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0, // Sẽ tính lại
                'status' => collect(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->random(),
                'shipping_address' => '123 Đường Nguyễn Trãi, Quận 1, TP.HCM',
                'phone_number' => '0901234567',
                'payment_status' => collect(['pending', 'paid', 'failed'])->random(),
                'payment_method' => collect(['cod', 'credit_card', 'bank_transfer'])->random(),
            ]);

            $numItems = rand(1, 3);
            $totalAmount = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 2);
                $price = $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalAmount += $price * $quantity;
            }

            $order->update(['total_amount' => $totalAmount]);
        }
    }
}
