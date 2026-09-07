@extends("layouts.admin")
@section("title", "Customers")
@section("content")
<div class="bg-white rounded-lg shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-stone-50 text-left"><tr><th class="p-3">Name</th><th>Email</th><th>Phone</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($customers as $customer)
        <tr class="border-t">
            <td class="p-3"><a href="{{ route("admin.customers.show", $customer) }}" class="text-maroon-700">{{ $customer->name }}</a></td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ $customer->status ? "Active" : "Disabled" }}</td>
            <td class="p-3">
                <form action="{{ route("admin.customers.toggle-status", $customer) }}" method="POST">
                    @csrf
                    <button class="text-maroon-700">{{ $customer->status ? "Disable" : "Enable" }}</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection
