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
        $category = Category::all();
        $brand = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // this is store product in database //
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        // product create in database //
        Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

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
    public function update(Request $request, string $id)
    {
        // this is update product in database //
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:products,slug,' . $product->id,
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        // update product in database //
        $product->update($request->all());

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
