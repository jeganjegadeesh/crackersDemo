<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Product Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Category</label>
        <select name="category_id" required class="w-full border rounded px-3 py-2">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Brand</label>
        <select name="brand_id" class="w-full border rounded px-3 py-2">
            <option value="">—</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id ?? null) == $brand->id)>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">MRP (₹)</label>
        <input type="number" step="0.01" name="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Selling Price (₹)</label>
        <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Stock Quantity</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Product Images / Videos</label>
        <input type="file" name="images[]" multiple accept="image/*,video/*" class="w-full border rounded px-3 py-2">
        <p class="text-xs text-stone-500 mt-1">JPG, PNG, WEBP, GIF, MP4, MOV, AVI or WEBM &mdash; up to 5MB each.</p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured ?? false))> Featured
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="status" value="1" @checked(old('status', $product->status ?? true))> Active
        </label>
    </div>
</div>
<button class="mt-6 bg-maroon-700 text-white px-6 py-2 rounded">Save Product</button>
