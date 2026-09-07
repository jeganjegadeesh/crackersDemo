@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('content')
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Items</h2>
            @foreach($order->items as $item)
            <div class="flex justify-between text-sm py-2 border-b last:border-0">
                <span>{{ $item->product_name }} &times; {{ $item->quantity }}</span>
                <span>₹{{ number_format($item->total, 2) }}</span>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Customer & Contact</h2>
            <p class="text-sm">{{ $order->user->name }} &middot; {{ $order->user->email }}</p>
            <p class="text-sm mt-1">
                Phone:
                <a href="tel:{{ $order->address->phone ?? $order->user->phone }}" class="text-maroon-700 font-medium">
                    {{ $order->address->phone ?? $order->user->phone }}
                </a>
                <span class="text-xs text-stone-500">(call to confirm the order and arrange payment)</span>
            </p>
            @if($order->address)
            <p class="text-sm mt-2">
                {{ $order->address->name }}<br>
                {{ $order->address->address }}, {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}
            </p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Order Status</h2>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-2">
                @csrf
                <select name="order_status" class="w-full border rounded px-3 py-2">
                    @foreach(['placed','confirmed','packed','shipped','out_for_delivery','delivered','cancelled','returned'] as $status)
                        <option value="{{ $status }}" @selected($order->order_status === $status)>{{ ucwords(str_replace('_',' ',$status)) }}</option>
                    @endforeach
                </select>
                <button class="w-full bg-maroon-700 text-white py-2 rounded">Update Status</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Payment</h2>
            <p class="text-sm mb-2">
                Method: <strong>{{ strtoupper($order->payment_method) }}</strong><br>
                Current status:
                <span class="px-2 py-0.5 rounded text-xs
                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
            <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST" class="space-y-2">
                @csrf
                <select name="payment_status" class="w-full border rounded px-3 py-2">
                    @foreach(['pending','paid','failed','refunded'] as $status)
                        <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="w-full bg-stone-800 text-white py-2 rounded text-sm">Update Payment Status</button>
            </form>
            <p class="text-xs text-stone-500 mt-2">
                Mark as "Paid" once payment is confirmed by phone, UPI, bank transfer, or on delivery.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 text-sm space-y-1">
            <div class="flex justify-between"><span>Subtotal</span><span>₹{{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span>Coupon Discount</span><span>-₹{{ number_format($order->coupon_discount, 2) }}</span></div>
            <div class="flex justify-between"><span>Delivery</span><span>₹{{ number_format($order->delivery_charge, 2) }}</span></div>
            <div class="flex justify-between font-bold border-t pt-2"><span>Total</span><span>₹{{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>
</div>
@endsection
