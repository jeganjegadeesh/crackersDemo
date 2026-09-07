@extends('layouts.app')
@section('title', 'Your Cart')
@section('content')
<h1 class="text-2xl font-bold mb-6">Your Cart</h1>

@if(!$cart || $cart->items->isEmpty())
    <p class="text-stone-500">Your cart is empty. <a href="{{ route('products.index') }}" class="text-maroon-700 underline">Browse products</a></p>
@else
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-3">
        @foreach($cart->items as $item)
        <div class="bg-white rounded-lg shadow p-4 flex flex-wrap sm:flex-nowrap items-center gap-3 sm:gap-4">
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-stone-100 rounded flex items-center justify-center text-2xl overflow-hidden shrink-0">
                @if($item->product->images->first())
                    @if($item->product->images->first()->is_video)
                        <video src="{{ Storage::url($item->product->images->first()->image) }}" class="w-full h-full object-cover" muted></video>
                    @else
                        <img src="{{ Storage::url($item->product->images->first()->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                    @endif
                @else
                    🧨
                @endif
            </div>
            <div class="flex-1 min-w-[140px]">
                <p class="font-medium text-sm sm:text-base">{{ $item->product->name }}</p>
                <p class="text-xs sm:text-sm text-stone-500">₹{{ number_format($item->price, 2) }} each</p>
            </div>
            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2 order-3 sm:order-none">
                @csrf @method('PATCH')
                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                       class="w-16 border rounded px-2 py-1" onchange="this.form.submit()">
            </form>
            <p class="w-20 sm:w-24 text-right font-semibold text-sm sm:text-base order-4 sm:order-none">₹{{ number_format($item->total, 2) }}</p>
            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="order-5 sm:order-none">
                @csrf @method('DELETE')
                <button class="text-red-500 text-xs sm:text-sm">Remove</button>
            </form>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-lg shadow p-4 h-fit">
        <h2 class="font-semibold mb-3">Order Summary</h2>
        <div class="flex justify-between text-sm mb-2">
            <span>Subtotal</span><span>₹{{ number_format($cart->subtotal, 2) }}</span>
        </div>

        @if($appliedCoupon)
            <div class="flex justify-between text-sm mb-2 text-green-600">
                <span>Coupon ({{ $appliedCoupon->code }})</span>
                <form action="{{ route('coupon.remove') }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="underline">Remove</button>
                </form>
            </div>
        @else
            <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2 mb-3">
                @csrf
                <input type="text" name="code" placeholder="Coupon code" class="flex-1 border rounded px-2 py-1 text-sm">
                <button class="bg-stone-800 text-white px-3 rounded text-sm">Apply</button>
            </form>
        @endif

        <a href="{{ route('checkout.index') }}" class="block text-center bg-maroon-700 text-white py-2 rounded mt-4">Proceed to Checkout</a>
    </div>
</div>
@endif
@endsection
