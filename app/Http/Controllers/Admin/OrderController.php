<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with("user")
            ->when($request->filled("status"), fn ($q) => $q->where("order_status", $request->status))
            ->latest()->paginate(20)->withQueryString();

        return view("admin.orders.index", compact("orders"));
    }

    public function show(Order $order)
    {
        $order->load("items.product", "user", "address");

        return view("admin.orders.show", compact("order"));
    }

    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            "payment_status" => ["required", "in:pending,paid,failed,refunded"],
        ]);

        $order->update($validated);

        return back()->with("status", "Payment status updated.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            "order_status" => ["required", "in:placed,confirmed,packed,shipped,out_for_delivery,delivered,cancelled,returned"],
        ]);

        DB::transaction(function () use ($order, $validated) {
            if (in_array($validated["order_status"], ["cancelled", "returned"]) && $order->canBeCancelled()) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment("stock", $item->quantity);
                    }
                }
            }

            $order->update(["order_status" => $validated["order_status"]]);
        });

        if ($request->wantsJson()) {
            return response()->json(["success" => true, "data" => ["order_status" => $order->order_status]]);
        }

        return back()->with("status", "Order status updated.");
    }
}
