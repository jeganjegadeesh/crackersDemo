@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="grid md:grid-cols-2 gap-8">
    <div>
        <div class="aspect-square bg-white rounded-lg shadow flex items-center justify-center text-7xl overflow-hidden">
            @if($product->images->first())
                @if($product->images->first()->is_video)
                    <video src="{{ Storage::url($product->images->first()->image) }}" class="w-full h-full object-cover rounded-lg" controls></video>
                @else
                    <img src="{{ Storage::url($product->images->first()->image) }}" class="w-full h-full object-cover rounded-lg" alt="{{ $product->name }}">
                @endif
            @else
                🧨
            @endif
        </div>
        <div class="flex gap-2 mt-3 flex-wrap">
            @foreach($product->images->skip(1) as $img)
                @if($img->is_video)
                    <video src="{{ Storage::url($img->image) }}" class="w-16 h-16 object-cover rounded border" muted></video>
                @else
                    <img src="{{ Storage::url($img->image) }}" class="w-16 h-16 object-cover rounded border" alt="{{ $product->name }}">
                @endif
            @endforeach
        </div>
    </div>
    <div>
        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
        <p class="text-sm text-stone-500">{{ $product->category->name }} @if($product->brand) &middot; {{ $product->brand->name }} @endif</p>
        <div class="mt-4 flex items-center gap-3">
            <span class="text-3xl font-bold text-maroon-700">₹{{ number_format($product->selling_price, 2) }}</span>
            @if($product->discount_percent > 0)
                <span class="text-lg text-stone-400 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                <span class="text-green-600 font-medium">{{ $product->discount_percent }}% off</span>
            @endif
        </div>
        <p class="mt-2 {{ $product->in_stock ? 'text-green-600' : 'text-red-500' }}">
            {{ $product->in_stock ? 'In stock (' . $product->stock . ' available)' : 'Out of stock' }}
        </p>
        <p class="mt-4 text-stone-700">{{ $product->description }}</p>

        @auth
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <form action="{{ route('cart.add') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center border rounded">
                    <button type="button" onclick="const i=this.nextElementSibling; i.stepDown(); i.dispatchEvent(new Event('change'))"
                            class="px-3 py-2 text-lg leading-none" @disabled(!$product->in_stock)>−</button>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock ?: 1 }}"
                           class="w-14 text-center border-0 focus:ring-0 py-2" @disabled(!$product->in_stock)>
                    <button type="button" onclick="const i=this.previousElementSibling; i.stepUp(); i.dispatchEvent(new Event('change'))"
                            class="px-3 py-2 text-lg leading-none" @disabled(!$product->in_stock)>+</button>
                </div>
                <button class="bg-maroon-700 text-white px-6 py-2 rounded disabled:opacity-40" @disabled(!$product->in_stock)>Add to Cart</button>
            </form>
            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                @csrf
                <button class="border px-4 py-2 rounded">♡ Wishlist</button>
            </form>
        </div>
        @else
        <a href="{{ route('login') }}" class="inline-block mt-6 bg-maroon-700 text-white px-6 py-2 rounded">Login to purchase</a>
        @endauth

        <div class="mt-8 border-t pt-6">
            <h2 class="font-semibold mb-3">Reviews</h2>
            @forelse($product->approvedReviews as $review)
                <div class="mb-3">
                    <p class="font-medium text-sm">{{ $review->user->name }} – {{ $review->rating }}/5</p>
                    <p class="text-sm text-stone-600">{{ $review->review }}</p>
                </div>
            @empty
                <p class="text-sm text-stone-500">No reviews yet.</p>
            @endforelse

            @auth
            <form action="{{ route('reviews.store') }}" method="POST" class="mt-4 space-y-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <select name="rating" class="border rounded px-2 py-1" required>
                    <option value="">Rating</option>
                    @for($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ $i }} stars</option>@endfor
                </select>
                <textarea name="review" rows="2" class="w-full border rounded px-2 py-1" placeholder="Write a review"></textarea>
                <button class="bg-maroon-700 text-white px-4 py-1.5 rounded text-sm">Submit Review</button>
            </form>
            @endauth
        </div>
    </div>
</div>

@if($related->count())
<section class="mt-12">
    <h2 class="text-xl font-bold mb-4">Related Products</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($related as $product)
            @include('customer.products._card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif
@endsection
