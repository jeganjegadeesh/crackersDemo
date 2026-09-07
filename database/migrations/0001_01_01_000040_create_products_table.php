<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained()->cascadeOnDelete();
            $table->foreignId("brand_id")->nullable()->constrained()->nullOnDelete();
            $table->string("name");
            $table->string("slug")->unique();
            $table->string("sku")->unique();
            $table->text("description")->nullable();
            $table->decimal("mrp", 10, 2);
            $table->decimal("selling_price", 10, 2);
            $table->unsignedInteger("stock")->default(0);
            $table->boolean("featured")->default(false);
            $table->boolean("status")->default(true);
            $table->timestamps();

            $table->index(["category_id", "status"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("products");
    }
};
