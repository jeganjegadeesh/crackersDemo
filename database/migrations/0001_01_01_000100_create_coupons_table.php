<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("coupons", function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->enum("type", ["percentage", "fixed"]);
            $table->decimal("value", 10, 2);
            $table->decimal("min_order", 10, 2)->default(0);
            $table->decimal("max_discount", 10, 2)->nullable();
            $table->date("start_date");
            $table->date("end_date");
            $table->unsignedInteger("usage_limit")->nullable();
            $table->boolean("status")->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("coupons");
    }
};
