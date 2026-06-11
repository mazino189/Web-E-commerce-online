@extends('layout.admin')

@section('content')

<h2>Edit Product</h2>
<hr>
<form 
    action="{{ route('admin.products.update', $product->id) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    {{-- Product Name --}}
    <div>
        <label>Product Name</label>
        <br>
        <input
            type="text"
            name="name"
            value="{{ old('name', $product->name) }}"
        >

        @error('name')
            <p>{{ $message }}</p>
        @enderror
    </div>
    <br>

    {{-- Slug --}}
    <div>
        <label>Slug</label>
        <br>
        <input
            type="text"
            name="slug"
            value="{{ old('slug', $product->slug) }}"
        >

        @error('slug')
            <p>{{ $message }}</p>
        @enderror
    </div>
    <br>

    {{-- Description --}}
    <div>
        <label>Description</label>
        <br>
        <textarea
            name="description"
            rows="5"
        >{{ old('description', $product->description) }}</textarea>
    </div>
    <br>

    {{-- Price --}}
    <div>
        <label>Price</label>
        <br>
        <input
            type="number"
            name="price"
            value="{{ old('price', $product->price) }}"
        >

        @error('price')
            <p>{{ $message }}</p>
        @enderror
    </div>
    <br>

    {{-- Stock --}}
    <div>
        <label>Stock</label>
        <br>
        <input
            type="number"
            name="stock"
            value="{{ old('stock', $product->stock) }}"
        >

        @error('stock')
            <p>{{ $message }}</p>
        @enderror
    </div>
    <br>

    {{-- Category --}}
    <div>
        <label>Category</label>
        <br>
        <select name="category_id">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option 
                    value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
            <p>{{ $message }}</p>
        @enderror   
    </div>
    <br>

    {{-- Brand --}}
    <div>
        <label>Brand</label>
        <br>
        <select name="brand_id">
            <option value="">Select Brand</option>
            @foreach ($brands as $brand)
                <option 
                    value="{{ $brand->id }}"
                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}
                >
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        {{-- Added error message display for brand --}}
        @error('brand_id')
            <p>{{ $message }}</p>
        @enderror
    </div>
    <br>

    {{-- Submit Button --}}
    <button type="submit">
        Update Product
    </button>
</form>
@endsection