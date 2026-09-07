@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Create an Account</h1>
    @if($errors->any())
        <div class="bg-red-100 text-red-700 text-sm rounded px-3 py-2 mb-4">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    <form action="{{ route('register') }}" method="POST" class="space-y-3">
        @csrf
        <input type="text" name="name" placeholder="Full name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2">
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2">
        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}" required class="w-full border rounded px-3 py-2">
        <input type="password" name="password" placeholder="Password" required class="w-full border rounded px-3 py-2">
        <input type="password" name="password_confirmation" placeholder="Confirm password" required class="w-full border rounded px-3 py-2">
        <button class="w-full bg-maroon-700 text-white py-2 rounded">Register</button>
    </form>

    <div class="flex items-center gap-3 my-4">
        <div class="flex-1 border-t"></div>
        <span class="text-xs text-stone-400">OR</span>
        <div class="flex-1 border-t"></div>
    </div>

    <a href="{{ route('auth.google.redirect') }}" class="w-full flex items-center justify-center gap-2 border rounded py-2 text-sm font-medium hover:bg-stone-50">
        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.66-.22-2.45H12v4.64h6.47a5.53 5.53 0 0 1-2.4 3.63v3h3.87c2.27-2.09 3.58-5.17 3.58-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.94-2.91l-3.87-3c-1.08.72-2.46 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.96H1.28v3.11A12 12 0 0 0 12 24z"/><path fill="#FBBC05" d="M5.27 14.28A7.2 7.2 0 0 1 4.89 12c0-.79.14-1.56.38-2.28V6.61H1.28A12 12 0 0 0 0 12c0 1.94.46 3.77 1.28 5.39l3.99-3.11z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.69 1.28 6.61l3.99 3.11C6.22 6.86 8.87 4.75 12 4.75z"/></svg>
        Continue with Google
    </a>
    <p class="text-sm mt-4">Already have an account? <a href="{{ route('login') }}" class="text-maroon-700 underline">Login</a></p>
</div>
@endsection
