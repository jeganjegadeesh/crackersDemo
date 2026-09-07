<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Flat delivery charge below this subtotal; free above it.
    private const DELIVERY_CHARGE = 60.00;
    private const FREE_DELIVERY_THRESHOLD = 999.00;

    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with("items.product")->first();

        abort_if(! $cart || $cart->items->isEmpty(), 302, redirect()->route("cart.index"));

        $coupon = session("coupon_code") ? Coupon::where("code", session("coupon_code"))->first() : null;

        return view("customer.checkout.index", [
            "cart" => $cart,
            "addresses" => $request->user()->addresses,
            "coupon" => $coupon,
            "totals" => $this->calculateTotals($cart->subtotal, $coupon),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "address_id" => ["required", "exists:addresses,id"],
        ]);

        $cart = $request->user()->cart()->with("items.product")->first();

        abort_if(! $cart || $cart->items->isEmpty(), 400, "Your cart is empty.");

        $coupon = session("coupon_code") ? Coupon::where("code", session("coupon_code"))->first() : null;
        $totals = $this->calculateTotals($cart->subtotal, $coupon);

        $order = DB::transaction(function () use ($request, $cart, $coupon, $totals, $validated) {
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    abort(422, "\"{$item->product->name}\" no longer has enough stock.");
                }
            }

            $order = Order::create([
                "user_id" => $request->user()->id,
                "address_id" => $validated["address_id"],
                "order_number" => "JC-" . strtoupper(Str::random(8)),
                "subtotal" => $cart->subtotal,
                "discount" => 0,
                "coupon_discount" => $totals["coupon_discount"],
                "delivery_charge" => $totals["delivery_charge"],
                "total" => $totals["total"],
                "payment_method" => "cod",
                "payment_status" => "pending",
                "order_status" => "placed",
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item->product_id,
                    "product_name" => $item->product->name,
                    "quantity" => $item->quantity,
                    "price" => $item->price,
                    "total" => $item->price * $item->quantity,
                ]);

                // Stock is deducted at order placement in this implementation;
                // adjust the stock-deduction stage here if the business prefers
                // to hold stock until confirmation instead.
                $item->product->decrement("stock", $item->quantity);
            }

            if ($coupon) {
                CouponUsage::create([
                    "coupon_id" => $coupon->id,
                    "user_id" => $request->user()->id,
                    "order_id" => $order->id,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        session()->forget("coupon_code");

        return redirect()->route("orders.show", $order)->with("status", "Order placed successfully!");
    }

    private function calculateTotals(float $subtotal, ?Coupon $coupon): array
    {
        $couponDiscount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $afterDiscount = $subtotal - $couponDiscount;
        $deliveryCharge = $afterDiscount >= self::FREE_DELIVERY_THRESHOLD ? 0 : self::DELIVERY_CHARGE;

        return [
            "subtotal" => $subtotal,
            "coupon_discount" => $couponDiscount,
            "delivery_charge" => $deliveryCharge,
            "total" => round($afterDiscount + $deliveryCharge, 2),
        ];
    }
}
