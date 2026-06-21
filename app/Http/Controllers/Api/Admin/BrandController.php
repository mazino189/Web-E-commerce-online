<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BrandController extends Controller
{
    public function index()
    {
        return BrandResource::collection(Brand::all());
    }

    public function store(StoreBrandRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('logo')->getRealPath(), [
                'folder' => 'ecommerce/brands',
            ])->getSecurePath();
            $validated['logo'] = $uploadedFileUrl;
        }

        $brand = Brand::create($validated);

        return BrandResource::make($brand)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Brand $brand): BrandResource
    {
        return new BrandResource($brand);
    }

    public function update(UpdateBrandRequest $request, Brand $brand): BrandResource
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('logo')->getRealPath(), [
                'folder' => 'ecommerce/brands',
            ])->getSecurePath();
            $validated['logo'] = $uploadedFileUrl;
        }

        $brand->update($validated);

        return new BrandResource($brand);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json(['message' => 'Brand deleted successfully.']);
    }
}
