<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "category_id", "brand_id", "name", "slug", "sku", "description",
        "mrp", "selling_price", "stock", "featured", "status",
    ];

    protected function casts(): array
    {
        return [
            "mrp" => "decimal:2",
            "selling_price" => "decimal:2",
            "featured" => "boolean",
            "status" => "boolean",
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy("sort_order");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where("status", "approved");
    }

    public function getDiscountPercentAttribute(): int
    {
        if ((float) $this->mrp <= 0) {
            return 0;
        }

        return (int) round((($this->mrp - $this->selling_price) / $this->mrp) * 100);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function scopeActive($query)
    {
        return $query->where("status", true);
    }
}
