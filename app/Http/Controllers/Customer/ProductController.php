<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(["category", "brand", "images"]);

        if ($request->filled("category")) {
            $query->whereHas("category", fn ($q) => $q->where("slug", $request->category));
        }

        if ($request->filled("min_price") || $request->filled("max_price")) {
            $query->whereBetween("selling_price", [
                $request->input("min_price", 0),
                $request->input("max_price", PHP_INT_MAX),
            ]);
        }

        if ($request->filled("q")) {
            $query->where("name", "like", "%" . $request->q . "%");
        }

        match ($request->input("sort")) {
            "price_asc" => $query->orderBy("selling_price"),
            "price_desc" => $query->orderByDesc("selling_price"),
            "newest" => $query->latest(),
            default => $query->orderBy("name"),
        };

        return view("customer.products.index", [
            "products" => $query->paginate(24)->withQueryString(),
            "categories" => Category::where("status", true)->get(),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()->with(["category", "brand", "images", "approvedReviews.user"])
            ->where("slug", $slug)->firstOrFail();

        $related = Product::active()->where("category_id", $product->category_id)
            ->where("id", "!=", $product->id)->take(4)->get();

        return view("customer.products.show", compact("product", "related"));
    }
}
