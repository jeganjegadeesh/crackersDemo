<div class="flex items-center gap-4 p-3 sm:p-4">
    <a href="{{ route('products.show', $product->slug) }}" class="shrink-0">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-stone-100 rounded flex items-center justify-center text-2xl overflow-hidden">
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

    <div class="flex-1 min-w-0">
        <a href="{{ route('products.show', $product->slug) }}" class="font-medium text-sm sm:text-base hover:text-maroon-700 line-clamp-1">{{ $product->name }}</a>
        <p class="text-xs text-stone-500">{{ $product->category->name }}</p>
        <div class="mt-1 flex items-center gap-2">
            <span class="font-bold text-maroon-700">₹{{ number_format($product->selling_price, 2) }}</span>
            @if($product->discount_percent > 0)
                <span class="text-xs text-stone-400 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                <span class="text-xs text-green-600">{{ $product->discount_percent }}% off</span>
            @endif
        </div>
        <p class="text-xs {{ $product->in_stock ? 'text-green-600' : 'text-red-500' }}">
            {{ $product->in_stock ? 'In stock' : 'Out of stock' }}
        </p>
    </div>

    @auth
    <div class="flex flex-col sm:flex-row gap-2 shrink-0">
        <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock ?: 1 }}"
                   class="w-14 border rounded px-1.5 py-1.5 text-xs text-center" @disabled(!$product->in_stock)>
            <button class="bg-maroon-700 text-white text-xs px-3 py-1.5 rounded disabled:opacity-40 whitespace-nowrap" @disabled(!$product->in_stock)>Add to Cart</button>
            <button type="submit" formaction="{{ route('wishlist.toggle', $product->id) }}" class="border px-2 py-1.5 rounded text-xs shrink-0">♡</button>
        </form>
    </div>
    @endauth
</div>
