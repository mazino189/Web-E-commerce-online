<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request): JsonResponse
    {
        $user = $request->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        $paymentStatus = $request->payment_method === 'cod' ? 'unpaid' : 'pending';

        try {
            $order = DB::transaction(function () use ($user, $cartItems, $request, $paymentStatus) {
                $productIds = $cartItems->pluck('product_id');
                $products = Product::lockForUpdate()->whereIn('id', $productIds)->get()->keyBy('id');

                $total = 0;

                foreach ($cartItems as $cartItem) {
                    $product = $products->get($cartItem->product_id);

                    if ($cartItem->quantity > $product->stock) {
                        throw new \RuntimeException("Insufficient stock. Only {$product->stock} available.");
                    }

                    $total += $product->price * $cartItem->quantity;
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'shipping_address' => $request->shipping_address,
                    'phone_number' => $request->phone_number,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $request->payment_method,
                ]);

                foreach ($cartItems as $cartItem) {
                    $product = $products->get($cartItem->product_id);

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock', $cartItem->quantity);
                }

                Cart::where('user_id', $user->id)->delete();

                return $order->load('items');
            });

            return OrderResource::make($order)->response()->setStatusCode(201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
