@extends('layouts.app')
@section('title', 'Products')
@section('content')
<div class="flex flex-col md:flex-row gap-6" x-data="{ filtersOpen: false, view: 'grid' }">
    <div class="md:hidden">
        <button @click="filtersOpen = !filtersOpen" class="w-full bg-white rounded-lg shadow px-4 py-2 text-sm font-medium flex justify-between items-center">
            Filters
            <span x-text="filtersOpen ? '−' : '+'"></span>
        </button>
    </div>

    <aside class="w-full md:w-56 shrink-0" :class="filtersOpen ? 'block' : 'hidden md:block'">
        <form method="GET" class="bg-white rounded-lg shadow p-4 space-y-3 text-sm">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <div>
                <h3 class="font-semibold mb-2">Category</h3>
                @foreach($categories as $category)
                <label class="flex items-center gap-2 mb-1">
                    <input type="radio" name="category" value="{{ $category->slug }}" @checked(request('category') === $category->slug) onchange="this.form.submit()">
                    {{ $category->name }}
                </label>
                @endforeach
            </div>
            <div>
                <h3 class="font-semibold mb-2">Price</h3>
                <div class="flex gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-1/2 border rounded px-2 py-1">
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-1/2 border rounded px-2 py-1">
                </div>
            </div>
            <button class="w-full bg-maroon-700 text-white py-1.5 rounded">Apply</button>
        </form>
    </aside>

    <div class="flex-1 min-w-0">
        <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
            <p class="text-sm text-stone-500">{{ $products->total() }} products</p>

            <div class="flex items-center gap-2">
                <div class="flex border rounded overflow-hidden">
                    <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-maroon-700 text-white' : 'bg-white text-stone-600'" class="px-2.5 py-1.5" aria-label="Grid view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/></svg>
                    </button>
                    <button @click="view = 'list'" :class="view === 'list' ? 'bg-maroon-700 text-white' : 'bg-white text-stone-600'" class="px-2.5 py-1.5" aria-label="List view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>

                <form method="GET">
                    @foreach(request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="sort" onchange="this.form.submit()" class="border rounded px-2 py-1.5 text-sm">
                        <option value="">Sort: Default</option>
                        <option value="price_asc" @selected(request('sort')==='price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort')==='price_desc')>Price: High to Low</option>
                        <option value="newest" @selected(request('sort')==='newest')>Newest</option>
                    </select>
                </form>
            </div>
        </div>

        @if($products->isEmpty())
            <p class="text-center text-stone-500 py-10">No products found.</p>
        @else
            <div x-show="view === 'grid'" class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                @foreach($products as $product)
                    @include('customer.products._card', ['product' => $product])
                @endforeach
            </div>
            <div x-show="view === 'list'" x-cloak class="flex flex-col divide-y bg-white rounded-lg shadow">
                @foreach($products as $product)
                    @include('customer.products._card_list', ['product' => $product])
                @endforeach
            </div>
        @endif

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</div>
@endsection
