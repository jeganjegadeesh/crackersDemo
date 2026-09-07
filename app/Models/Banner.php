<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ["title", "image", "link", "sort_order", "status"];

    protected function casts(): array
    {
        return ["status" => "boolean"];
    }

    public function scopeActive($query)
    {
        return $query->where("status", true)->orderBy("sort_order");
    }
}
