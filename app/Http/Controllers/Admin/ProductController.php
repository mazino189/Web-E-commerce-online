<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // show all products in admin panel //
        $product = Product::latest()->paginate(10);
        return view('admin.products.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // this is create form for product in admin panel //
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\StoreProductRequest $request)
    {
        // this is store product in database //
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $uploadedFileUrl = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), ['folder' => 'products'])['secure_url'];
            $validatedData['image'] = $uploadedFileUrl;
        }

        // product create in database //
        Product::create($validatedData);

        // redirect to product index with success message //
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // this is show product details in admin panel //
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // this is edit form for product in admin panel //
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\UpdateProductRequest $request, string $id)
    {
        // this is update product in database //
        $product = Product::findOrFail($id);
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $uploadedFileUrl = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), ['folder' => 'products'])['secure_url'];
            $validatedData['image'] = $uploadedFileUrl;
        }

        // update product in database //
        $product->update($validatedData);

        // redirect to product index with success message //
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // this is delete product from database //
        $product = Product::findOrFail($id);
        $product->delete();

        // redirect to product index with success message //
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}
