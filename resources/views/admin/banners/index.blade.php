@extends("layouts.admin")
@section("title", "Banners")
@section("content")
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 grid grid-cols-2 gap-4">
        @foreach($banners as $banner)
        <div class="bg-white rounded-lg shadow p-3">
            <img src="{{ Storage::url($banner->image) }}" class="w-full h-28 object-cover rounded mb-2">
            <p class="font-medium text-sm">{{ $banner->title }}</p>
            <p class="text-xs text-stone-500">{{ $banner->status ? "Active" : "Disabled" }} &middot; order {{ $banner->sort_order }}</p>
            <form action="{{ route("admin.banners.destroy", $banner) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete?')">
                @csrf @method("DELETE")
                <button class="text-red-500 text-xs">Delete</button>
            </form>
        </div>
        @endforeach
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Add Banner</h2>
        <form action="{{ route("admin.banners.store") }}" method="POST" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <input type="text" name="title" placeholder="Title" required class="w-full border rounded px-3 py-2">
            <input type="file" name="image" accept="image/*" required class="w-full border rounded px-3 py-2">
            <input type="text" name="link" placeholder="Link (optional)" class="w-full border rounded px-3 py-2">
            <input type="number" name="sort_order" placeholder="Sort order" class="w-full border rounded px-3 py-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="status" value="1" checked> Active</label>
            <button class="bg-maroon-700 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</div>
@endsection
