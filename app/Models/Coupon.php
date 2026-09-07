<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        "code", "type", "value", "min_order", "max_discount",
        "start_date", "end_date", "usage_limit", "status",
    ];

    protected function casts(): array
    {
        return [
            "start_date" => "date",
            "end_date" => "date",
            "status" => "boolean",
        ];
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValidNow(): bool
    {
        $today = Carbon::today();

        return $this->status
            && $today->between($this->start_date, $this->end_date)
            && ($this->usage_limit === null || $this->usages()->count() < $this->usage_limit);
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order) {
            return 0;
        }

        $discount = $this->type === "percentage"
            ? $subtotal * ($this->value / 100)
            : $this->value;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return round(min($discount, $subtotal), 2);
    }
}
