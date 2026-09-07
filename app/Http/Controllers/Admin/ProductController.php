<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(["category", "brand"])
            ->when($request->filled("q"), fn ($q) => $q->where("name", "like", "%" . $request->q . "%"))
            ->latest()->paginate(20)->withQueryString();

        return view("admin.products.index", compact("products"));
    }

    public function create()
    {
        return view("admin.products.create", [
            "categories" => Category::all(),
            "brands" => Brand::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $product = Product::create([
            ...$validated,
            "slug" => Str::slug($validated["name"]) . "-" . Str::random(5),
        ]);

        $this->storeImages($request, $product);

        return redirect()->route("admin.products.index")->with("status", "Product created.");
    }

    public function edit(Product $product)
    {
        return view("admin.products.edit", [
            "product" => $product->load("images"),
            "categories" => Category::all(),
            "brands" => Brand::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validated($request, $product->id);

        $product->update($validated);

        $this->storeImages($request, $product);

        return redirect()->route("admin.products.index")->with("status", "Product updated.");
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with("status", "Product deleted.");
    }

    public function toggleStatus(Product $product)
    {
        $product->update(["status" => ! $product->status]);

        return response()->json(["success" => true, "data" => ["status" => $product->status]]);
    }

    private function validated(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            "category_id" => ["required", "exists:categories,id"],
            "brand_id" => ["nullable", "exists:brands,id"],
            "name" => ["required", "string", "max:255"],
            "sku" => ["required", "string", "max:100", "unique:products,sku," . $productId],
            "description" => ["nullable", "string"],
            "mrp" => ["required", "numeric", "min:0"],
            "selling_price" => ["required", "numeric", "min:0", "lte:mrp"],
            "stock" => ["required", "integer", "min:0"],
            "featured" => ["nullable", "boolean"],
            "status" => ["nullable", "boolean"],
            "images" => ["nullable", "array"],
            // Each file may be an image or a short video, capped at 5MB (max is in kilobytes).
            "images.*" => ["file", "mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm", "max:5120"],
        ]);
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile("images")) {
            return;
        }

        $startOrder = $product->images()->max("sort_order") + 1;

        foreach ($request->file("images") as $index => $file) {
            $path = $file->store("products", "public");

            ProductImage::create([
                "product_id" => $product->id,
                "image" => $path,
                "sort_order" => $startOrder + $index,
            ]);
        }
    }
}
