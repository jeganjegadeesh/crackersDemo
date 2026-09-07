@extends("layouts.admin")
@section("title", "Inventory")
@section("content")
<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3 text-amber-600">Low Stock (≤10)</h2>
        @foreach($lowStock as $product)
            <div class="flex justify-between text-sm py-1 border-b last:border-0">
                <span>{{ $product->name }}</span><span>{{ $product->stock }}</span>
            </div>
        @endforeach
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3 text-red-600">Out of Stock</h2>
        @foreach($outOfStock as $product)
            <div class="text-sm py-1 border-b last:border-0">{{ $product->name }}</div>
        @endforeach
    </div>
</div>
@endsection
