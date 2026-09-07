<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        "name", "email", "phone", "google_id", "password", "role", "status",
    ];

    protected $hidden = [
        "password", "remember_token",
    ];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "status" => "boolean",
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ["super_admin", "staff_admin"]);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === "super_admin";
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function notifications_custom()
    {
        return $this->hasMany(Notification::class, "user_id");
    }
}
