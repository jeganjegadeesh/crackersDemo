<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        return view("admin.reports.inventory", [
            "lowStock" => Product::where("stock", ">", 0)->where("stock", "<=", 10)->get(),
            "outOfStock" => Product::where("stock", 0)->get(),
            "sold" => OrderItem::select("product_id", DB::raw("SUM(quantity) as sold"))
                ->groupBy("product_id")->with("product")->get(),
        ]);
    }

    public function lowStock()
    {
        $products = Product::where("stock", "<=", 10)->get(["id", "name", "stock"]);

        return response()->json(["success" => true, "data" => $products]);
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate(["stock" => ["required", "integer", "min:0"]]);

        $product->update($validated);

        return back()->with("status", "Stock updated.");
    }
}
