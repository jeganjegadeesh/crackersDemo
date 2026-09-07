@extends('layouts.app')
@section('title', 'Wishlist')
@section('content')
<h1 class="text-2xl font-bold mb-6">Your Wishlist</h1>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse($wishlists as $wish)
        @include('customer.products._card', ['product' => $wish->product])
    @empty
        <p class="text-stone-500 col-span-4">Your wishlist is empty.</p>
    @endforelse
</div>
@endsection
