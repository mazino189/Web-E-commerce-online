<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
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
                $total = 0;

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

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
                    $product = $cartItem->product;

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

            return response()->json($order, 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
