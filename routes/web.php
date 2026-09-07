<?php

use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer-facing routes
|--------------------------------------------------------------------------
*/

Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/products", [ProductController::class, "index"])->name("products.index");
Route::get("/products/{slug}", [ProductController::class, "show"])->name("products.show");
Route::get("/category/{slug}", [ProductController::class, "index"])->name("category.show");

// Guest-only auth routes
Route::middleware("guest")->group(function () {
    Route::get("register", [RegisteredUserController::class, "create"])->name("register");
    Route::post("register", [RegisteredUserController::class, "store"]);
    Route::get("login", [AuthenticatedSessionController::class, "create"])->name("login");
    Route::post("login", [AuthenticatedSessionController::class, "store"]);
    Route::get("forgot-password", [PasswordResetLinkController::class, "create"])->name("password.request");
    Route::post("forgot-password", [PasswordResetLinkController::class, "store"])->name("password.email");
    Route::get("auth/google/redirect", [GoogleController::class, "redirect"])->name("auth.google.redirect");
});

// The Google callback runs before the session has an authenticated user, but it
// must NOT sit behind the "guest" middleware, since a returning/linked user may
// already have a session in some edge cases. Keep it outside the guest group.
Route::get("auth/google/callback", [GoogleController::class, "callback"])->name("auth.google.callback");

Route::post("logout", [AuthenticatedSessionController::class, "destroy"])
    ->middleware("auth")->name("logout");

// Authenticated customer routes
Route::middleware("auth")->group(function () {
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart/add", [CartController::class, "add"])->name("cart.add");
    Route::patch("/cart/update/{item}", [CartController::class, "update"])->name("cart.update");
    Route::delete("/cart/remove/{item}", [CartController::class, "remove"])->name("cart.remove");
    Route::post("/coupon/apply", [CartController::class, "applyCoupon"])->name("coupon.apply");
    Route::delete("/coupon/remove", [CartController::class, "removeCoupon"])->name("coupon.remove");

    Route::get("/wishlist", [WishlistController::class, "index"])->name("wishlist.index");
    Route::post("/wishlist/toggle/{product}", [WishlistController::class, "toggle"])->name("wishlist.toggle");

    Route::get("/checkout", [CheckoutController::class, "index"])->name("checkout.index");
    Route::post("/checkout", [CheckoutController::class, "store"])->name("checkout.store");

    Route::get("/orders", [OrderController::class, "index"])->name("orders.index");
    Route::get("/orders/{order}", [OrderController::class, "show"])->name("orders.show");
    Route::get("/orders/{order}/track", [OrderController::class, "track"])->name("orders.track");
    Route::post("/orders/{order}/cancel", [OrderController::class, "cancel"])->name("orders.cancel");

    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");

    Route::get("/addresses", [AddressController::class, "index"])->name("addresses.index");
    Route::post("/addresses", [AddressController::class, "store"])->name("addresses.store");
    Route::delete("/addresses/{address}", [AddressController::class, "destroy"])->name("addresses.destroy");

    Route::post("/reviews", [ReviewController::class, "store"])->name("reviews.store");
});

/*
|--------------------------------------------------------------------------
| Admin routes (auth + admin middleware)
|--------------------------------------------------------------------------
*/

Route::prefix("admin")->name("admin.")->middleware(["auth", "admin"])->group(function () {
    Route::get("dashboard", [DashboardController::class, "index"])->name("dashboard");
    Route::get("dashboard/sales-chart", [DashboardController::class, "salesChart"])->name("dashboard.sales-chart");

    Route::resource("products", AdminProductController::class)->except(["show"]);
    Route::post("products/{product}/toggle-status", [AdminProductController::class, "toggleStatus"])
        ->name("products.toggle-status");

    Route::resource("categories", AdminCategoryController::class)->only(["index", "store", "update", "destroy"]);
    Route::resource("brands", AdminBrandController::class)->only(["index", "store", "update", "destroy"]);

    Route::get("inventory", [InventoryController::class, "index"])->name("inventory.index");
    Route::get("inventory/low-stock", [InventoryController::class, "lowStock"])->name("inventory.low-stock");
    Route::patch("inventory/{product}", [InventoryController::class, "updateStock"])->name("inventory.update");

    Route::get("orders", [AdminOrderController::class, "index"])->name("orders.index");
    Route::get("orders/{order}", [AdminOrderController::class, "show"])->name("orders.show");
    Route::post("orders/{order}/update-status", [AdminOrderController::class, "updateStatus"])->name("orders.update-status");
    Route::post("orders/{order}/update-payment", [AdminOrderController::class, "updatePayment"])->name("orders.update-payment");

    Route::get("customers", [AdminCustomerController::class, "index"])->name("customers.index");
    Route::get("customers/{customer}", [AdminCustomerController::class, "show"])->name("customers.show");
    Route::post("customers/{customer}/toggle-status", [AdminCustomerController::class, "toggleStatus"])
        ->name("customers.toggle-status");

    Route::resource("coupons", AdminCouponController::class)->only(["index", "store", "update", "destroy"]);
    Route::resource("banners", AdminBannerController::class)->only(["index", "store", "update", "destroy"]);

    Route::get("reviews", [AdminReviewController::class, "index"])->name("reviews.index");
    Route::post("reviews/{review}/moderate", [AdminReviewController::class, "moderate"])->name("reviews.moderate");
    Route::delete("reviews/{review}", [AdminReviewController::class, "destroy"])->name("reviews.destroy");

    Route::get("reports", [ReportController::class, "index"])->name("reports.index");
});
