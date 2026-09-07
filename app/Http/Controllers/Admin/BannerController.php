<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return view("admin.banners.index", ["banners" => Banner::orderBy("sort_order")->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => ["required", "string", "max:255"],
            "image" => ["required", "image"],
            "link" => ["nullable", "string", "max:255"],
            "sort_order" => ["nullable", "integer"],
            "status" => ["nullable", "boolean"],
        ]);

        $validated["image"] = $request->file("image")->store("banners", "public");
        $validated["sort_order"] = $validated["sort_order"] ?? 0;

        Banner::create($validated);

        return back()->with("status", "Banner created.");
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            "title" => ["required", "string", "max:255"],
            "image" => ["nullable", "image"],
            "link" => ["nullable", "string", "max:255"],
            "sort_order" => ["nullable", "integer"],
            "status" => ["nullable", "boolean"],
        ]);

        if ($request->hasFile("image")) {
            $validated["image"] = $request->file("image")->store("banners", "public");
        }
        $validated["sort_order"] = $validated["sort_order"] ?? 0;

        $banner->update($validated);

        return back()->with("status", "Banner updated.");
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return back()->with("status", "Banner deleted.");
    }
}
