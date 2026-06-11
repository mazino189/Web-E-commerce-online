@extends('layout.admin')

@section('content')

<h2>Product List</h2>

<hr>

<a href="{{ route('admin.products.create') }}">
    Add Product
</a>

<hr>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Brand</th>
        <th>Action</th>
    </tr>

    @foreach($products as $product)
        <tr>
            <td>
                {{ $product->id }}
            </td>
            <td>
                {{ $product->name }}
            </td>
            <td>
                ${{ $product->price }}
            </td>
            <td>
                {{ $product->stock }}
            </td>
            <td>
                {{ $product->category->name ?? 'No Category' }}
            </td>
            <td>
                {{ $product->brand->name ?? 'No Brand' }}
            </td>
            <td>
                {{-- SHOW --}}
                <a href="{{ route('admin.products.show', $product->id) }}">
                    Show
                </a>
                |
                {{-- EDIT --}}
                <a href="{{ route('admin.products.edit', $product->id) }}">
                    Edit
                </a>
                |
                {{-- DELETE (With JavaScript confirmation dialog added) --}}
                <form
                    action="{{ route('admin.products.destroy', $product->id) }}"
                    method="POST"
                    style="display:inline-block;"
                    onsubmit="return confirm('Are you sure you want to delete this product?');"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

{{-- Pagination Links (Optional but recommended) --}}
@if(method_exists($products, 'links'))
    <div style="margin-top: 15px;">
        {{ $products->links() }}
    </div>
@endif

@endsection