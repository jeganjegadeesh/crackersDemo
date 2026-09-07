<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view("admin.dashboard", [
            "salesTotal" => Order::where("payment_status", "paid")->sum("total"),
            "orderCount" => Order::count(),
            "customerCount" => User::where("role", "customer")->count(),
            "productCount" => Product::count(),
            "recentOrders" => Order::with("user")->latest()->take(8)->get(),
            "topProducts" => OrderItem::select("product_name", DB::raw("SUM(quantity) as qty"))
                ->groupBy("product_name")->orderByDesc("qty")->take(5)->get(),
        ]);
    }

    public function salesChart(Request $request)
    {
        $data = Order::selectRaw("DATE(created_at) as date, SUM(total) as total")
            ->where("created_at", ">=", now()->subDays(30))
            ->groupBy("date")->orderBy("date")->get();

        return response()->json(["success" => true, "data" => $data]);
    }
}
