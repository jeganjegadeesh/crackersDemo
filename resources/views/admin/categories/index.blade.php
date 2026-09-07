@extends("layouts.admin")
@section("title", "Categories")
@section("content")
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left"><tr><th class="p-3">Name</th><th>Products</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($categories as $category)
                <tr class="border-t">
                    <td class="p-3">{{ $category->name }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->status ? "Active" : "Disabled" }}</td>
                    <td class="p-3">
                        <form action="{{ route("admin.categories.destroy", $category) }}" method="POST" onsubmit="return confirm('Delete?')">
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
        <h2 class="font-semibold mb-3">Add Category</h2>
        <form action="{{ route("admin.categories.store") }}" method="POST" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <input type="text" name="name" placeholder="Category name" required class="w-full border rounded px-3 py-2">
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="status" value="1" checked> Active</label>
            <button class="bg-maroon-700 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</div>
@endsection
