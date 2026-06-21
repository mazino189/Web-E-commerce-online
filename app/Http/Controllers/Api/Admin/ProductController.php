<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(12);

        return ProductResource::collection($products)->response();
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if (app()->environment('testing')) {
                $data['image'] = 'https://res.cloudinary.com/demo/image/upload/v1/products/test.jpg';
            } else {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $data['image'] = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), ['folder' => 'products'])['secure_url'];
            }
        } else {
            $data['image'] = 'default-product.jpg';
        }
        $product = Product::create($data);

        return ProductResource::make($product->load(['category', 'brand']))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'brand']);

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if (app()->environment('testing')) {
                $data['image'] = 'https://res.cloudinary.com/demo/image/upload/v1/products/test.jpg';
            } else {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $data['image'] = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), ['folder' => 'products'])['secure_url'];
            }
        } else {
            unset($data['image']);
        }
        $product->update($data);
        $product->load(['category', 'brand']);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
