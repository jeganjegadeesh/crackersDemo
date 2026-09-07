<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(["user", "product"])
            ->when($request->filled("status"), fn ($q) => $q->where("status", $request->status))
            ->latest()->paginate(20);

        return view("admin.reviews.index", compact("reviews"));
    }

    public function moderate(Request $request, Review $review)
    {
        $validated = $request->validate(["status" => ["required", "in:approved,rejected"]]);

        $review->update($validated);

        if ($request->wantsJson()) {
            return response()->json(["success" => true, "data" => ["status" => $review->status]]);
        }

        return back()->with("status", "Review updated.");
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with("status", "Review deleted.");
    }
}
