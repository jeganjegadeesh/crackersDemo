@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<h1 class="text-2xl font-bold mb-6">Checkout</h1>
<form action="{{ route('checkout.store') }}" method="POST" class="grid md:grid-cols-3 gap-6">
    @csrf
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Delivery Address</h2>
            @forelse($addresses as $address)
            <label class="flex items-start gap-3 mb-3 border rounded p-3 cursor-pointer">
                <input type="radio" name="address_id" value="{{ $address->id }}" @checked($loop->first) required>
                <span class="text-sm">
                    <strong>{{ $address->name }}</strong> – {{ $address->phone }}<br>
                    {{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                </span>
            </label>
            @empty
                <p class="text-sm text-stone-500 mb-2">No saved addresses yet.</p>
            @endforelse
            <a href="{{ route('addresses.index') }}" class="text-sm text-maroon-700 underline">Manage addresses</a>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">How Payment Works</h2>
            <p class="text-sm text-stone-600 bg-amber-50 border border-amber-200 rounded p-3">
                After you place the order, our team will call you on the phone number attached to your
                delivery address to confirm the order and arrange payment (cash on delivery, UPI, or
                bank transfer). No payment is collected automatically at this step.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 h-fit">
        <h2 class="font-semibold mb-3">Order Summary</h2>
        <div class="text-sm space-y-1">
            <div class="flex justify-between"><span>Subtotal</span><span>₹{{ number_format($totals['subtotal'], 2) }}</span></div>
            <div class="flex justify-between"><span>Coupon Discount</span><span>-₹{{ number_format($totals['coupon_discount'], 2) }}</span></div>
            <div class="flex justify-between"><span>Delivery Charge</span><span>₹{{ number_format($totals['delivery_charge'], 2) }}</span></div>
            <div class="flex justify-between font-bold text-base border-t pt-2 mt-2"><span>Total</span><span>₹{{ number_format($totals['total'], 2) }}</span></div>
        </div>
        <button class="w-full bg-maroon-700 text-white py-2 rounded mt-4">Place Order</button>
    </div>
</form>
@endsection
