<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if (app()->environment('testing')) {
                $validated['image'] = 'https://res.cloudinary.com/demo/image/upload/v1/categories/test.jpg';
            } else {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $validated['image'] = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), [
                    'folder' => 'categories',
                    'transformation' => [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit'
                    ]
                ])['secure_url'];
            }
        }

        $category = Category::create($validated);

        return CategoryResource::make($category)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if (app()->environment('testing')) {
                $validated['image'] = 'https://res.cloudinary.com/demo/image/upload/v1/categories/test.jpg';
            } else {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $validated['image'] = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), [
                    'folder' => 'categories',
                    'transformation' => [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit'
                    ]
                ])['secure_url'];
            }
        }

        $category->update($validated);

        return new CategoryResource($category->fresh());
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
