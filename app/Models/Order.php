<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_FLOW = [
        "placed", "confirmed", "packed", "shipped", "out_for_delivery", "delivered",
    ];

    protected $fillable = [
        "user_id", "address_id", "order_number", "subtotal", "discount",
        "coupon_discount", "delivery_charge", "total", "payment_method",
        "payment_status", "order_status",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, ["placed", "confirmed", "packed"]);
    }

    public function statusIndex(): int
    {
        return array_search($this->order_status, self::STATUS_FLOW, true) ?: 0;
    }
}
