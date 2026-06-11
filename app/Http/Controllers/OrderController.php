<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::with('items')->where('user_id', auth()->id())->latest()->get();

        return response()->json($orders);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $user = $request->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        try {
            $order = DB::transaction(function () use ($user, $cartItems, $request) {
                $total = 0;
                $items = [];

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

                    if ($cartItem->quantity > $product->stock) {
                        throw new \RuntimeException("Insufficient stock. Only {$product->stock} available.");
                    }

                    $total += $product->price * $cartItem->quantity;
                    $items[] = [
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $product->price,
                    ];
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'shipping_address' => $request->shipping_address,
                    'phone_number' => $request->phone_number,
                ]);

                foreach ($items as $item) {
                    $orderItem = $order->items()->create($item);

                    $product = $orderItem->product;
                    $product->decrement('stock', $item['quantity']);
                }

                Cart::where('user_id', $user->id)->delete();

                return $order->load('items');
            });

            return response()->json($order, 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Order $order): JsonResponse
    {
        $order->load('items');

        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($order);
    }
}
