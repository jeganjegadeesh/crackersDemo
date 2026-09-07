<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = $request->user()->wishlists()->with("product.images")->get();

        return view("customer.wishlist.index", compact("wishlists"));
    }

    public function toggle(Request $request, int $product)
    {
        $existing = $request->user()->wishlists()->where("product_id", $product)->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
        } else {
            Wishlist::create(["user_id" => $request->user()->id, "product_id" => $product]);
            $inWishlist = true;
        }

        if ($request->wantsJson()) {
            return response()->json(["success" => true, "data" => ["in_wishlist" => $inWishlist]]);
        }

        return back()->with("status", $inWishlist ? "Added to wishlist." : "Removed from wishlist.");
    }
}
