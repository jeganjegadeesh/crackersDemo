<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ["product_id", "image", "sort_order"];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getIsVideoAttribute(): bool
    {
        $videoExtensions = ["mp4", "mov", "avi", "webm"];

        return in_array(strtolower(pathinfo($this->image, PATHINFO_EXTENSION)), $videoExtensions, true);
    }
}
