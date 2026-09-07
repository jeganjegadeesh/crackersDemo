@extends("layouts.admin")
@section("title", "Coupons")
@section("content")
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left"><tr><th class="p-3">Code</th><th>Type</th><th>Value</th><th>Valid</th><th>Used</th><th></th></tr></thead>
            <tbody>
            @foreach($coupons as $coupon)
                <tr class="border-t">
                    <td class="p-3">{{ $coupon->code }}</td>
                    <td>{{ $coupon->type }}</td>
                    <td>{{ $coupon->type === "percentage" ? $coupon->value."%" : "₹".$coupon->value }}</td>
                    <td>{{ $coupon->start_date->format("d M") }} - {{ $coupon->end_date->format("d M Y") }}</td>
                    <td>{{ $coupon->usages_count }}{{ $coupon->usage_limit ? "/".$coupon->usage_limit : "" }}</td>
                    <td class="p-3">
                        <form action="{{ route("admin.coupons.destroy", $coupon) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method("DELETE")
                            <button class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Add Coupon</h2>
        <form action="{{ route("admin.coupons.store") }}" method="POST" class="space-y-2">
            @csrf
            <input type="text" name="code" placeholder="Coupon code" required class="w-full border rounded px-3 py-2 uppercase">
            <select name="type" class="w-full border rounded px-3 py-2">
                <option value="percentage">Percentage</option>
                <option value="fixed">Fixed Amount</option>
            </select>
            <input type="number" step="0.01" name="value" placeholder="Value" required class="w-full border rounded px-3 py-2">
            <input type="number" step="0.01" name="min_order" placeholder="Minimum order" class="w-full border rounded px-3 py-2">
            <input type="number" step="0.01" name="max_discount" placeholder="Max discount (for %)" class="w-full border rounded px-3 py-2">
            <label class="block text-xs text-stone-500">Start date</label>
            <input type="date" name="start_date" required class="w-full border rounded px-3 py-2">
            <label class="block text-xs text-stone-500">End date</label>
            <input type="date" name="end_date" required class="w-full border rounded px-3 py-2">
            <input type="number" name="usage_limit" placeholder="Usage limit" class="w-full border rounded px-3 py-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="status" value="1" checked> Active</label>
            <button class="bg-maroon-700 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</div>
@endsection
