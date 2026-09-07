<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->foreignId("address_id")->nullable()->constrained()->nullOnDelete();
            $table->string("order_number")->unique();
            $table->decimal("subtotal", 10, 2);
            $table->decimal("discount", 10, 2)->default(0);
            $table->decimal("coupon_discount", 10, 2)->default(0);
            $table->decimal("delivery_charge", 10, 2)->default(0);
            $table->decimal("total", 10, 2);
            $table->enum("payment_method", ["cod", "online"])->default("cod");
            $table->enum("payment_status", ["pending", "paid", "failed", "refunded"])->default("pending");
            $table->enum("order_status", [
                "placed", "confirmed", "packed", "shipped", "out_for_delivery", "delivered", "cancelled", "returned",
            ])->default("placed");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("orders");
    }
};
