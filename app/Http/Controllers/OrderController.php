<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::with('items')->where('user_id', auth()->id())->latest()->get();

        return OrderResource::collection($orders)->response();
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
                $productIds = $cartItems->pluck('product_id');
                $products = Product::lockForUpdate()->whereIn('id', $productIds)->get()->keyBy('id');

                $total = 0;
                $items = [];

                foreach ($cartItems as $cartItem) {
                    $product = $products->get($cartItem->product_id);

                    if ($cartItem->quantity > $product->stock) {
                        throw new \RuntimeException("Insufficient stock. Only {$product->stock} available.");
                    }

                    $total += $product->price * $cartItem->quantity;
                    $items[] = [
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $product->price,
                        '_product' => $product,
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
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);

                    $item['_product']->decrement('stock', $item['quantity']);
                }

                Cart::where('user_id', $user->id)->delete();

                return $order->load('items');
            });

            return OrderResource::make($order)->response()->setStatusCode(201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $order->load('items');

        return OrderResource::make($order)->response();
    }
}
