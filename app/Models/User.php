<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Payment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
    // public function cart()
    // {
    //     return $this->hasOne(Cart::class);
    // }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
