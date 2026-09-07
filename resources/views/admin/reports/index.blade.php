@extends("layouts.admin")
@section("title", "Reports")
@section("content")
<form method="GET" class="flex gap-2 mb-6 text-sm">
    <input type="date" name="from" value="{{ $from }}" class="border rounded px-3 py-1.5">
    <input type="date" name="to" value="{{ $to }}" class="border rounded px-3 py-1.5">
    <button class="bg-stone-800 text-white px-4 rounded">Filter</button>
</form>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Daily Sales</h2>
        @foreach($dailySales as $row)
            <div class="flex justify-between text-sm py-1 border-b last:border-0"><span>{{ $row->date }}</span><span>₹{{ number_format($row->total,2) }}</span></div>
        @endforeach
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Product Sales</h2>
        @foreach($productSales as $row)
            <div class="flex justify-between text-sm py-1 border-b last:border-0"><span>{{ $row->product_name }} ({{ $row->qty }})</span><span>₹{{ number_format($row->revenue,2) }}</span></div>
        @endforeach
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Payment Report</h2>
        @foreach($paymentReport as $row)
            <div class="flex justify-between text-sm py-1 border-b last:border-0"><span>{{ strtoupper($row->payment_method) }} ({{ $row->count }})</span><span>₹{{ number_format($row->total,2) }}</span></div>
        @endforeach
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Cancelled / Returned Orders</h2>
        <p class="text-3xl font-bold">{{ $cancelledReturned }}</p>
    </div>
</div>
@endsection
