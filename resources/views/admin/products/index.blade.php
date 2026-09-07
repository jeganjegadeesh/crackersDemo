@extends('layouts.admin')
@section('title', 'Products')
@section('content')
<div class="flex justify-between items-center mb-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products" class="border rounded px-3 py-1.5 text-sm">
        <button class="bg-stone-800 text-white px-3 rounded text-sm">Search</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="bg-maroon-700 text-white px-4 py-2 rounded text-sm">+ Add Product</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-stone-50 text-left">
        <tr><th class="p-3">Name</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr class="border-t">
            <td class="p-3">{{ $product->name }}</td>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->category->name }}</td>
            <td>₹{{ number_format($product->selling_price, 2) }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST">
                    @csrf
                    <button class="px-2 py-0.5 rounded text-xs {{ $product->status ? 'bg-green-100 text-green-700' : 'bg-stone-200 text-stone-500' }}">
                        {{ $product->status ? 'Active' : 'Disabled' }}
                    </button>
                </form>
            </td>
            <td class="p-3 space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="text-maroon-700">Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
