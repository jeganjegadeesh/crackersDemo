<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view("customer.home", [
            "banners" => Banner::active()->get(),
            "categories" => Category::where("status", true)->get(),
            "featured" => Product::active()->where("featured", true)->latest()->take(8)->get(),
            "bestSellers" => Product::active()->latest()->take(8)->get(),
        ]);
    }
}
