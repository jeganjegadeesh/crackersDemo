<div class="bg-white rounded-lg shadow overflow-hidden group">
    <a href="{{ route('products.show', $product->slug) }}">
        <div class="aspect-square bg-stone-100 flex items-center justify-center text-5xl">
            @if($product->images->first())
                @if($product->images->first()->is_video)
                    <video src="{{ Storage::url($product->images->first()->image) }}" class="w-full h-full object-cover" muted></video>
                @else
                    <img src="{{ Storage::url($product->images->first()->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                @endif
            @else
                🧨
            @endif
        </div>
    </a>
    <div class="p-3">
        <a href="{{ route('products.show', $product->slug) }}" class="font-medium text-sm line-clamp-2 hover:text-maroon-700">{{ $product->name }}</a>
        <div class="mt-1 flex items-center gap-2">
            <span class="font-bold text-maroon-700">₹{{ number_format($product->selling_price, 2) }}</span>
            @if($product->discount_percent > 0)
                <span class="text-xs text-stone-400 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                <span class="text-xs text-green-600">{{ $product->discount_percent }}% off</span>
            @endif
        </div>
        <p class="text-xs mt-1 {{ $product->in_stock ? 'text-green-600' : 'text-red-500' }}">
            {{ $product->in_stock ? 'In stock' : 'Out of stock' }}
        </p>
        @auth
        <div class="mt-2 space-y-1.5">
            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock ?: 1 }}"
                       class="w-14 border rounded px-1.5 py-1 text-xs text-center" @disabled(!$product->in_stock)>
                <button class="flex-1 bg-maroon-700 text-white text-xs py-1.5 rounded disabled:opacity-40" @disabled(!$product->in_stock)>Add to Cart</button>
                <button type="submit" formaction="{{ route('wishlist.toggle', $product->id) }}" class="border px-2 py-1.5 rounded text-xs shrink-0">♡</button>
            </form>
        </div>
        @endauth
    </div>
</div>
