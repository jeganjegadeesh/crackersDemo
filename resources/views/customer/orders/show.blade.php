@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('content')
<h1 class="text-2xl font-bold mb-2">Order {{ $order->order_number }}</h1>
<p class="text-sm text-stone-500 mb-6">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h2 class="font-semibold mb-3">Status Timeline</h2>
    <div class="flex flex-wrap gap-2">
        @foreach(\App\Models\Order::STATUS_FLOW as $i => $step)
            <span class="px-3 py-1 rounded-full text-xs {{ $i <= $order->statusIndex() && !in_array($order->order_status, ['cancelled','returned']) ? 'bg-green-600 text-white' : 'bg-stone-200 text-stone-500' }}">
                {{ ucwords(str_replace('_', ' ', $step)) }}
            </span>
        @endforeach
        @if(in_array($order->order_status, ['cancelled', 'returned']))
            <span class="px-3 py-1 rounded-full text-xs bg-red-600 text-white">{{ ucfirst($order->order_status) }}</span>
        @endif
    </div>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Items</h2>
        @foreach($order->items as $item)
        <div class="flex justify-between text-sm py-2 border-b last:border-0">
            <span>{{ $item->product_name }} &times; {{ $item->quantity }}</span>
            <span>₹{{ number_format($item->total, 2) }}</span>
        </div>
        @endforeach
    </div>
    <div class="bg-white rounded-lg shadow p-4 h-fit">
        <h2 class="font-semibold mb-3">Summary</h2>
        <div class="text-sm space-y-1">
            <div class="flex justify-between"><span>Subtotal</span><span>₹{{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span>Coupon Discount</span><span>-₹{{ number_format($order->coupon_discount, 2) }}</span></div>
            <div class="flex justify-between"><span>Delivery</span><span>₹{{ number_format($order->delivery_charge, 2) }}</span></div>
            <div class="flex justify-between font-bold border-t pt-2 mt-2"><span>Total</span><span>₹{{ number_format($order->total, 2) }}</span></div>
        </div>
        @if($order->address)
        <div class="mt-4 text-sm">
            <h3 class="font-semibold mb-1">Delivery Address</h3>
            <p>{{ $order->address->name }}, {{ $order->address->address }}, {{ $order->address->city }} - {{ $order->address->pincode }}</p>
        </div>
        @endif
        @if($order->canBeCancelled())
        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-4">
            @csrf
            <button class="w-full border border-red-500 text-red-500 py-1.5 rounded text-sm">Cancel Order</button>
        </form>
        @endif
    </div>
</div>
@endsection
