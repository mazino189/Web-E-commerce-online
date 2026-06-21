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
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'demo'),
                    'api_key'    => env('CLOUDINARY_API_KEY', 'demo'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'demo'),
                ],
                'url' => ['secure' => true]
            ]);

            $uploadApi = new UploadApi();
            $result = $uploadApi->upload($request->file('image')->getRealPath(), [
                'folder' => 'categories',
                'transformation' => [
                    'width' => 800,
                    'height' => 800,
                    'crop' => 'limit'
                ]
            ]);
            $validated['image'] = $result['secure_url'];
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
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'demo'),
                    'api_key'    => env('CLOUDINARY_API_KEY', 'demo'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'demo'),
                ],
                'url' => ['secure' => true]
            ]);

            $uploadApi = new UploadApi();
            $result = $uploadApi->upload($request->file('image')->getRealPath(), [
                'folder' => 'categories',
                'transformation' => [
                    'width' => 800,
                    'height' => 800,
                    'crop' => 'limit'
                ]
            ]);
            $validated['image'] = $result['secure_url'];
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
