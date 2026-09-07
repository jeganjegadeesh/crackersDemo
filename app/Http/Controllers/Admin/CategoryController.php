<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view("admin.categories.index", ["categories" => Category::withCount("products")->latest()->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "image" => ["nullable", "image"],
            "status" => ["nullable", "boolean"],
        ]);

        if ($request->hasFile("image")) {
            $validated["image"] = $request->file("image")->store("categories", "public");
        }

        $validated["slug"] = Str::slug($validated["name"]);

        Category::create($validated);

        return back()->with("status", "Category created.");
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "image" => ["nullable", "image"],
            "status" => ["nullable", "boolean"],
        ]);

        if ($request->hasFile("image")) {
            $validated["image"] = $request->file("image")->store("categories", "public");
        }

        $validated["slug"] = Str::slug($validated["name"]);

        $category->update($validated);

        return back()->with("status", "Category updated.");
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with("status", "Category deleted.");
    }
}
