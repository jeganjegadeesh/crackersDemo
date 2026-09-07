<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return view("admin.brands.index", ["brands" => Brand::withCount("products")->latest()->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "status" => ["nullable", "boolean"],
        ]);

        $validated["slug"] = Str::slug($validated["name"]);

        Brand::create($validated);

        return back()->with("status", "Brand created.");
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "status" => ["nullable", "boolean"],
        ]);

        $validated["slug"] = Str::slug($validated["name"]);

        $brand->update($validated);

        return back()->with("status", "Brand updated.");
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return back()->with("status", "Brand deleted.");
    }
}
