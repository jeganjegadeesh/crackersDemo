@extends("layouts.admin")
@section("title", "Orders")
@section("content")
<div class="bg-white rounded-lg shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-stone-50 text-left"><tr><th class="p-3">Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($orders as $order)
        <tr class="border-t">
            <td class="p-3">{{ $order->order_number }}</td>
            <td>{{ $order->user->name }}</td>
            <td>₹{{ number_format($order->total, 2) }}</td>
            <td>{{ strtoupper($order->payment_method) }} / {{ $order->payment_status }}</td>
            <td>{{ str_replace("_"," ",$order->order_status) }}</td>
            <td class="p-3"><a href="{{ route("admin.orders.show", $order) }}" class="text-maroon-700">View</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
