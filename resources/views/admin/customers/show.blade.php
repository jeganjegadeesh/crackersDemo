@extends("layouts.admin")
@section("title", $customer->name)
@section("content")
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <p><strong>{{ $customer->name }}</strong></p>
    <p class="text-sm text-stone-500">{{ $customer->email }} &middot; {{ $customer->phone }}</p>
</div>
<h2 class="font-semibold mb-3">Order History</h2>
<div class="bg-white rounded-lg shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-stone-50 text-left"><tr><th class="p-3">Order #</th><th>Total</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($customer->orders as $order)
        <tr class="border-t"><td class="p-3">{{ $order->order_number }}</td><td>₹{{ number_format($order->total,2) }}</td><td>{{ $order->order_status }}</td></tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
