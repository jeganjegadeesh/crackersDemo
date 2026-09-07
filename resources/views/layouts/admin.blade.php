<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin – Jegan Crackers')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 font-sans" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-30 md:hidden" style="display:none"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="w-56 bg-maroon-800 text-white shrink-0 fixed inset-y-0 left-0 z-40 transform transition-transform duration-200 md:translate-x-0 md:static">
            <div class="px-4 py-4 font-bold text-lg border-b border-maroon-600 flex justify-between items-center">
                <span>🧨 Admin Panel</span>
                <button @click="sidebarOpen = false" class="md:hidden">✕</button>
            </div>
            <nav class="flex flex-col p-2 text-sm gap-1">
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Products</a>
                <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Categories</a>
                <a href="{{ route('admin.brands.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Brands</a>
                <a href="{{ route('admin.inventory.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Inventory</a>
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Orders</a>
                <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Customers</a>
                <a href="{{ route('admin.coupons.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Coupons</a>
                <a href="{{ route('admin.banners.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Banners</a>
                <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Reviews</a>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded hover:bg-maroon-700">Reports</a>
                <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-maroon-700 mt-4 text-amber-300">← View Storefront</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-maroon-700">Logout</button>
                </form>
            </nav>
        </aside>

        <div class="flex-1 min-w-0">
            <header class="bg-white border-b px-4 sm:px-6 py-3 flex justify-between items-center gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = true" class="md:hidden p-1 -ml-1" aria-label="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-semibold truncate">@yield('title', 'Dashboard')</h1>
                </div>
                <span class="text-sm text-stone-500 whitespace-nowrap hidden sm:inline">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            </header>
            <main class="p-4 sm:p-6 overflow-x-auto">
                @if (session('status'))
                    <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
