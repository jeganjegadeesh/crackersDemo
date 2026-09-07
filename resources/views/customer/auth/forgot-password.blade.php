@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Forgot Password</h1>
    @if (session('status'))
        <div class="bg-green-100 text-green-700 text-sm rounded px-3 py-2 mb-4">{{ session('status') }}</div>
    @endif
    <form action="{{ route('password.email') }}" method="POST" class="space-y-3">
        @csrf
        <input type="email" name="email" placeholder="Email" required class="w-full border rounded px-3 py-2">
        <button class="w-full bg-maroon-700 text-white py-2 rounded">Send Reset Link</button>
    </form>
</div>
@endsection
