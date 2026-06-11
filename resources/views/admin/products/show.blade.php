@extends('layouts.admin')

@section('content')

<h2>Product Detail</h2>

<hr>

<div>

    <p><b>ID:</b> {{ $product->id }}</p>

    <p><b>Name:</b> {{ $product->name }}</p>

    <p><b>Slug:</b> {{ $product->slug }}</p>

    <p><b>Description:</b> {{ $product->description }}</p>

    <p><b>Price:</b> ${{ $product->price }}</p>

    <p><b>Stock:</b> {{ $product->stock }}</p>

    <p><b>Category:</b> {{ $product->category->name ?? 'No Category' }}</p>

    <p><b>Brand:</b> {{ $product->brand->name ?? 'No Brand' }}</p>

    <p><b>Created At:</b> {{ $product->created_at }}</p>

    <p><b>Updated At:</b> {{ $product->updated_at }}</p>

</div>

<hr>

<a href="{{ route('admin.products.index') }}">
    Back to list
</a>

@endsection