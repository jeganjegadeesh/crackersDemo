<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Jegan Admin',
            'email' => 'admin@jegancrackers.test',
            'phone' => '9999999999',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        $customer = User::create([
            'name' => 'Demo Customer',
            'email' => 'customer@jegancrackers.test',
            'phone' => '8888888888',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        Cart::create(['user_id' => $customer->id]);

        $categories = collect(['Sparklers', 'Flower Pots', 'Rockets', 'Chakras', 'Gift Boxes'])
            ->map(fn ($name) => Category::create(['name' => $name, 'slug' => Str::slug($name)]));

        $brand = Brand::create(['name' => 'Jegan Standard', 'slug' => 'jegan-standard']);

        $categories->each(function (Category $category) use ($brand) {
            for ($i = 1; $i <= 4; $i++) {
                $name = "{$category->name} Type {$i}";
                Product::create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . Str::random(4),
                    'sku' => strtoupper(Str::random(8)),
                    'description' => 'A festive favourite, safety-tested and ready for Diwali celebrations.',
                    'mrp' => $mrp = rand(100, 1000),
                    'selling_price' => round($mrp * 0.8, 2),
                    'stock' => rand(0, 200),
                    'featured' => $i === 1,
                    'status' => true,
                ]);
            }
        });

        Coupon::create([
            'code' => 'DIWALI10',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 500,
            'max_discount' => 200,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonths(2),
            'usage_limit' => 500,
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'FLAT100',
            'type' => 'fixed',
            'value' => 100,
            'min_order' => 999,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonths(2),
            'status' => true,
        ]);
    }
}
