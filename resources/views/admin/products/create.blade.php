{{-- This is the create product form in the admin panel --}}
@extends('layout.admin')

@section('content')

{{-- Header for create new product --}}
<h2>Create New Product</h2>

<hr>

{{-- Form for create new product --}}
<form 
    action="{{ route('admin.products.store') }}"
    method="POST"
>
    {{-- CSRF token for security --}}
    @csrf

    {{-- Product Name --}}
    <div>
        <label>Product name</label>
        <br>
        <input 
            type="text"
            name="name"
            value="{{ old('name') }}"
        >
    
        {{-- Error message for product name validation --}}
        @error('name')
            <p>{{ $message }}</p>
        @enderror
    </div>

    {{-- Product Slug --}}
    <div>
        <label>Slug</label>
        <br>
        <input 
            type="text"
            name="slug"
            value="{{ old('slug') }}"
        >

        {{-- Error message for slug validation --}}
        @error('slug')
            <p>{{ $message }}</p>
        @enderror
    </div>

    {{-- Product Description --}}
    <div>
        <label>Description</label>
        <br>
        <textarea
            name="description"  
            rows="5"
        >{{ old('description') }}</textarea>
    </div>

    <br>

    {{-- Product Price --}}
    <div>
        <label>Price</label>
        <br>
        <input 
            type="number"
            name="price"
            value="{{ old('price') }}"
        >

        @error('price')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    {{-- Product Stock --}}
    <div>
        <label>Stock</label>
        <br>
        <input
            type="number"
            name="stock"
            value="{{ old('stock') }}"
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
            @foreach($categories as $category)
                <option value="{{ $category->id }}">
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
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}">
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        @error('brand_id')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    {{-- Submit Button --}}
    <button type="submit">
        Create Product
    </button>

</form>

@endsection