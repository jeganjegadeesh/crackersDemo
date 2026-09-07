@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<h1 class="text-2xl font-bold mb-6">My Profile</h1>
<div class="max-w-lg bg-white rounded-lg shadow p-6">
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-3">
        @csrf @method('PATCH')
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2">
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2">
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2">
        <button class="bg-maroon-700 text-white px-4 py-2 rounded">Save Changes</button>
    </form>
</div>
<div class="mt-4">
    <a href="{{ route('addresses.index') }}" class="text-maroon-700 underline text-sm">Manage Addresses</a>
</div>
@endsection
