@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-stone-500">Total Sales (Paid)</p>
        <p class="text-2xl font-bold">₹{{ number_format($salesTotal, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-stone-500">Orders</p>
        <p class="text-2xl font-bold">{{ $orderCount }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-stone-500">Customers</p>
        <p class="text-2xl font-bold">{{ $customerCount }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-stone-500">Products</p>
        <p class="text-2xl font-bold">{{ $productCount }}</p>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Recent Orders</h2>
        <table class="w-full text-sm">
            <thead><tr class="text-left text-stone-500"><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($recentOrders as $order)
                <tr class="border-t">
                    <td class="py-1"><a href="{{ route('admin.orders.show', $order) }}" class="text-maroon-700">{{ $order->order_number }}</a></td>
                    <td>{{ $order->user->name }}</td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                    <td>{{ str_replace('_',' ',$order->order_status) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Top-Selling Products</h2>
        <table class="w-full text-sm">
            <thead><tr class="text-left text-stone-500"><th>Product</th><th>Qty Sold</th></tr></thead>
            <tbody>
            @foreach($topProducts as $row)
                <tr class="border-t"><td class="py-1">{{ $row->product_name }}</td><td>{{ $row->qty }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
