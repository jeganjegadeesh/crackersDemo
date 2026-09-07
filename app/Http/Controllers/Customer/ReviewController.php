<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            "product_id" => ["required", "exists:products,id"],
            "rating" => ["required", "integer", "min:1", "max:5"],
            "review" => ["nullable", "string", "max:2000"],
        ]);

        Review::updateOrCreate(
            ["user_id" => $request->user()->id, "product_id" => $validated["product_id"]],
            ["rating" => $validated["rating"], "review" => $validated["review"] ?? null, "status" => "pending"]
        );

        return back()->with("status", "Thanks! Your review has been submitted for approval.");
    }
}
