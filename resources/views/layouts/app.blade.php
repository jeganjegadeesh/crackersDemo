<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jegan Crackers')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-800 font-sans">
    <header class="bg-maroon-700 text-white sticky top-0 z-40 shadow" x-data="{ menuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="text-lg sm:text-xl font-bold flex items-center gap-2 shrink-0">
                🧨 <span>Jegan Crackers</span>
            </a>

            <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search crackers..."
                       class="w-full rounded-l px-3 py-1.5 text-stone-900 focus:outline-none">
                <button class="bg-amber-500 px-4 rounded-r font-medium">Search</button>
            </form>

            <nav class="hidden md:flex items-center gap-4 text-sm">
                <a href="{{ route('wishlist.index') }}">Wishlist</a>
                <a href="{{ route('cart.index') }}">Cart</a>
                @auth
                    <a href="{{ route('orders.index') }}">Orders</a>
                    <a href="{{ route('profile.edit') }}">{{ Str::limit(auth()->user()->name, 12) }}</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-amber-500 text-maroon-900 px-2 py-1 rounded">Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}" class="bg-amber-500 text-maroon-900 px-2 py-1 rounded">Register</a>
                @endauth
            </nav>

            <button @click="menuOpen = !menuOpen" class="md:hidden p-2 -mr-2" aria-label="Menu">
                <svg x-show="!menuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="menuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div x-show="menuOpen" x-cloak @click.outside="menuOpen = false" class="md:hidden border-t border-maroon-600 px-4 py-3 space-y-3" style="display:none">
            <form action="{{ route('products.index') }}" method="GET" class="flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search crackers..."
                       class="w-full rounded-l px-3 py-2 text-stone-900 focus:outline-none">
                <button class="bg-amber-500 px-4 rounded-r font-medium">Go</button>
            </form>
            <nav class="flex flex-col gap-3 text-sm pt-1">
                <a href="{{ route('wishlist.index') }}">Wishlist</a>
                <a href="{{ route('cart.index') }}">Cart</a>
                @auth
                    <a href="{{ route('orders.index') }}">Orders</a>
                    <a href="{{ route('profile.edit') }}">My Profile ({{ Str::limit(auth()->user()->name, 16) }})</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-amber-500 text-maroon-900 px-3 py-1.5 rounded inline-block w-fit">Admin Panel</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-left">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}" class="bg-amber-500 text-maroon-900 px-3 py-1.5 rounded inline-block w-fit">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6 pb-24">
        @if (session('status'))
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2 text-sm">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-stone-900 text-stone-300 text-sm mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6">
            &copy; {{ date('Y') }} Jegan Crackers. Sale of fireworks is subject to applicable local regulations.
        </div>
    </footer>

    @auth
    @php
        $cartCount = auth()->user()->cart?->items()->sum('quantity') ?? 0;
        $wishlistCount = auth()->user()->wishlists()->count();
    @endphp
    <div class="fixed bottom-5 right-4 z-30 flex flex-col gap-3">
        <a href="{{ route('wishlist.index') }}" title="Wishlist"
           class="relative w-12 h-12 rounded-full bg-white text-maroon-700 shadow-lg border flex items-center justify-center hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318a4.5 4.5 0 1 1 6.364 6.364L12 21l-7.682-8.318a4.5 4.5 0 0 1 0-6.364z"/>
            </svg>
            @if($wishlistCount > 0)
                <span class="absolute -top-1 -right-1 bg-amber-500 text-maroon-900 text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $wishlistCount }}</span>
            @endif
        </a>

        <a href="{{ route('cart.index') }}" title="Cart"
           class="relative w-12 h-12 rounded-full bg-maroon-700 text-white shadow-lg flex items-center justify-center hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.706 2.602-7.184.075-.302-.152-.591-.463-.591H5.106M7.5 14.25 5.106 5.478M7.5 14.25l-1.5 5.25M15 18.75a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3ZM8.25 18.75a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/>
            </svg>
            @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 bg-amber-500 text-maroon-900 text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $cartCount }}</span>
            @endif
        </a>

        <a href="{{ route('orders.index') }}" title="My Orders"
           class="w-12 h-12 rounded-full bg-white text-maroon-700 shadow-lg border flex items-center justify-center hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l4.414 4.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
            </svg>
        </a>
    </div>
    @endauth
</body>
</html>
