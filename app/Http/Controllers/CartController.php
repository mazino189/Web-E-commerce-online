<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cartItems = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        return CartResource::collection($cartItems)->response();
    }

    public function store(CartRequest $request): JsonResponse
    {
        $product = Product::findOrFail($request->product_id);
        $existing = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = $request->quantity + ($existing?->quantity ?? 0);

        if ($newQuantity > $product->stock) {
            return response()->json([
                'message' => "Insufficient stock. Only {$product->stock} available.",
            ], 422);
        }

        if ($existing) {
            $existing->update(['quantity' => $newQuantity]);

            return CartResource::make($existing)->response()->setStatusCode(200);
        }

        $cart = Cart::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
        ]);

        return CartResource::make($cart)->response()->setStatusCode(201);
    }

    public function update(CartRequest $request, Cart $cart): JsonResponse
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $product = $cart->product;

        if ($request->quantity > $product->stock) {
            return response()->json([
                'message' => "Insufficient stock. Only {$product->stock} available.",
            ], 422);
        }

        $cart->update(['quantity' => $request->quantity]);

        return CartResource::make($cart)->response()->setStatusCode(200);
    }

    public function destroy(Request $request, Cart $cart): JsonResponse
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $cart->delete();

        return response()->json(['message' => 'Cart item removed.'], 200);
    }
}
