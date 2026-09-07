<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with("items.product.images")->first();

        return view("customer.cart.index", [
            "cart" => $cart,
            "appliedCoupon" => session("coupon_code")
                ? Coupon::where("code", session("coupon_code"))->first()
                : null,
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            "product_id" => ["required", "exists:products,id"],
            "quantity" => ["nullable", "integer", "min:1"],
        ]);

        $product = Product::findOrFail($validated["product_id"]);
        $cart = $request->user()->cart()->firstOrCreate([]);

        $item = $cart->items()->firstOrNew(["product_id" => $product->id]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + ($validated["quantity"] ?? 1);
        $item->quantity = min($item->quantity, $product->stock ?: $item->quantity);
        $item->price = $product->selling_price;
        $item->save();

        return $this->jsonOrRedirect($request, "Product added to cart.", [
            "cart_count" => $cart->items()->sum("quantity"),
        ]);
    }

    public function update(Request $request, int $item)
    {
        $validated = $request->validate(["quantity" => ["required", "integer", "min:1"]]);

        $cartItem = $request->user()->cart->items()->findOrFail($item);
        $cartItem->update(["quantity" => min($validated["quantity"], $cartItem->product->stock)]);

        return $this->jsonOrRedirect($request, "Cart updated.");
    }

    public function remove(Request $request, int $item)
    {
        $request->user()->cart->items()->where("id", $item)->delete();

        return $this->jsonOrRedirect($request, "Item removed from cart.");
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate(["code" => ["required", "string"]]);

        $coupon = Coupon::where("code", $validated["code"])->first();
        $subtotal = $request->user()->cart?->subtotal ?? 0;

        if (! $coupon || ! $coupon->isValidNow()) {
            return $this->jsonOrRedirect($request, "This coupon is not valid.", [], success: false);
        }

        if ($subtotal < $coupon->min_order) {
            return $this->jsonOrRedirect(
                $request,
                "Minimum order of ₹{$coupon->min_order} required for this coupon.",
                [],
                success: false
            );
        }

        session(["coupon_code" => $coupon->code]);

        return $this->jsonOrRedirect($request, "Coupon applied.", [
            "discount" => $coupon->calculateDiscount($subtotal),
        ]);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget("coupon_code");

        return $this->jsonOrRedirect($request, "Coupon removed.");
    }

    private function jsonOrRedirect(Request $request, string $message, array $data = [], bool $success = true)
    {
        if ($request->wantsJson()) {
            return response()->json(["success" => $success, "message" => $message, "data" => $data]);
        }

        return back()->with($success ? "status" : "error", $message);
    }
}
