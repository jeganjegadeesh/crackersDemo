<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input("from", now()->subDays(30)->toDateString());
        $to = $request->input("to", now()->toDateString());

        $orders = Order::whereBetween("created_at", [$from, $to]);

        return view("admin.reports.index", [
            "from" => $from,
            "to" => $to,
            "dailySales" => (clone $orders)->selectRaw("DATE(created_at) as date, SUM(total) as total")
                ->groupBy("date")->orderBy("date")->get(),
            "productSales" => OrderItem::whereHas("order", fn ($q) => $q->whereBetween("created_at", [$from, $to]))
                ->select("product_name", DB::raw("SUM(quantity) as qty"), DB::raw("SUM(total) as revenue"))
                ->groupBy("product_name")->orderByDesc("revenue")->get(),
            "paymentReport" => (clone $orders)->select("payment_method", DB::raw("COUNT(*) as count"), DB::raw("SUM(total) as total"))
                ->groupBy("payment_method")->get(),
            "cancelledReturned" => (clone $orders)->whereIn("order_status", ["cancelled", "returned"])->count(),
        ]);
    }
}
