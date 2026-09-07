@extends('layouts.app')
@section('title', 'Jegan Crackers – Home')
@section('content')
<div class="space-y-10">
    @if($banners->count())
    <div class="relative rounded-lg overflow-hidden shadow" x-data="{ active: 0 }"
         @if($banners->count() > 1) x-init="setInterval(() => active = (active + 1) % {{ $banners->count() }}, 4500)" @endif>
        @foreach($banners as $i => $banner)
        <a href="{{ $banner->link ?: route('products.index') }}"
           x-show="active === {{ $i }}" x-cloak
           class="block relative">
            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}"
                 class="w-full h-44 sm:h-64 md:h-80 object-cover">
            <div class="absolute inset-0 bg-black/30 flex items-end">
                <h2 class="text-white text-lg sm:text-2xl font-bold p-4 sm:p-6">{{ $banner->title }}</h2>
            </div>
        </a>
        @endforeach

        @if($banners->count() > 1)
        <div class="absolute bottom-3 right-4 flex gap-1.5">
            @foreach($banners as $i => $banner)
                <button @click="active = {{ $i }}" :class="active === {{ $i }} ? 'bg-white' : 'bg-white/50'" class="w-2 h-2 rounded-full" aria-label="Show banner {{ $i + 1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <section>
        <h2 class="text-xl font-bold mb-4">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md">
                <div class="text-3xl mb-2">🎆</div>
                <div class="font-medium">{{ $category->name }}</div>
            </a>
            @endforeach
        </div>
    </section>

    <section>
        <h2 class="text-xl font-bold mb-4">Featured Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($featured as $product)
                @include('customer.products._card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <section>
        <h2 class="text-xl font-bold mb-4">Best Sellers</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($bestSellers as $product)
                @include('customer.products._card', ['product' => $product])
            @endforeach
        </div>
    </section>
</div>
@endsection
