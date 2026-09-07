<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view("admin.coupons.index", ["coupons" => Coupon::withCount("usages")->latest()->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated["min_order"] = $validated["min_order"] ?? 0;

        Coupon::create($validated);

        return back()->with("status", "Coupon created.");
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $this->validated($request, $coupon->id);
        $validated["min_order"] = $validated["min_order"] ?? 0;

        $coupon->update($validated);

        return back()->with("status", "Coupon updated.");
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with("status", "Coupon deleted.");
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            "code" => ["required", "string", "max:50", "unique:coupons,code," . $id],
            "type" => ["required", "in:percentage,fixed"],
            "value" => ["required", "numeric", "min:0"],
            "min_order" => ["nullable", "numeric", "min:0"],
            "max_discount" => ["nullable", "numeric", "min:0"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date", "after_or_equal:start_date"],
            "usage_limit" => ["nullable", "integer", "min:1"],
            "status" => ["nullable", "boolean"],
        ]);
    }
}
