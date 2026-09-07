@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
<h1 class="text-2xl font-bold mb-6">My Orders</h1>
<div class="space-y-3">
    @forelse($orders as $order)
    <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-lg shadow p-4 hover:shadow-md">
        <div class="flex justify-between">
            <div>
                <p class="font-semibold">{{ $order->order_number }}</p>
                <p class="text-sm text-stone-500">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} item(s)</p>
            </div>
            <div class="text-right">
                <p class="font-bold">₹{{ number_format($order->total, 2) }}</p>
                <span class="text-xs uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800">{{ str_replace('_', ' ', $order->order_status) }}</span>
            </div>
        </div>
    </a>
    @empty
        <p class="text-stone-500">You haven't placed any orders yet.</p>
    @endforelse
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
