<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Assumes the default `users` migration from `laravel new` already exists.
// This migration adds the extra fields Jegan Crackers needs.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->string("phone")->nullable()->after("email");
            $table->enum("role", ["customer", "super_admin", "staff_admin"])
                ->default("customer")->after("phone");
            $table->boolean("status")->default(true)->after("role");
        });
    }

    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn(["phone", "role", "status"]);
        });
    }
};
