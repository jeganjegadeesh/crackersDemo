@extends('layouts.app')
@section('title', 'My Addresses')
@section('content')
<h1 class="text-2xl font-bold mb-6">My Addresses</h1>
<div class="grid md:grid-cols-2 gap-6">
    <div class="space-y-3">
        @forelse($addresses as $address)
        <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
            <div class="text-sm">
                <p class="font-medium">{{ $address->name }} @if($address->is_default)<span class="text-xs text-amber-600">(Default)</span>@endif</p>
                <p>{{ $address->phone }}</p>
                <p>{{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
            </div>
            <form action="{{ route('addresses.destroy', $address) }}" method="POST">
                @csrf @method('DELETE')
                <button class="text-red-500 text-sm">Delete</button>
            </form>
        </div>
        @empty
            <p class="text-stone-500">No addresses saved yet.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Add New Address</h2>
        <form action="{{ route('addresses.store') }}" method="POST" class="space-y-2">
            @csrf
            <input type="text" name="name" placeholder="Full name" required class="w-full border rounded px-3 py-2">
            <input type="text" name="phone" placeholder="Phone" required class="w-full border rounded px-3 py-2">
            <textarea name="address" placeholder="Address" required class="w-full border rounded px-3 py-2"></textarea>
            <div class="grid grid-cols-3 gap-2">
                <input type="text" name="city" placeholder="City" required class="border rounded px-3 py-2">
                <input type="text" name="state" placeholder="State" required class="border rounded px-3 py-2">
                <input type="text" name="pincode" placeholder="Pincode" required class="border rounded px-3 py-2">
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_default" value="1"> Set as default</label>
            <button class="bg-maroon-700 text-white px-4 py-2 rounded">Save Address</button>
        </form>
    </div>
</div>
@endsection
