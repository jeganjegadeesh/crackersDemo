<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with("items")->latest()->paginate(10);

        return view("customer.orders.index", compact("orders"));
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load("items.product", "address");

        return view("customer.orders.show", compact("order"));
    }

    public function track(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return response()->json([
            "success" => true,
            "data" => [
                "status" => $order->order_status,
                "steps" => Order::STATUS_FLOW,
                "current_index" => $order->statusIndex(),
            ],
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->canBeCancelled(), 422, "This order can no longer be cancelled.");

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment("stock", $item->quantity);
                }
            }

            $order->update(["order_status" => "cancelled"]);
        });

        return back()->with("status", "Order cancelled.");
    }
}
