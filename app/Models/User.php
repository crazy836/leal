<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo_path',
        'birthdate',
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
        'password' => 'hashed',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path 
            ? asset('storage/' . $this->profile_photo_path) 
            : asset('default-avatar.png');
    }
    
    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    
    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * Get the addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    
    /**
     * Get the wishlist items for the user.
     */
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }
    
    /**
     * Get the reviews for the user.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
    
    /**
     * Get the compare products for the user.
     */
    public function compareProducts()
    {
        return $this->hasMany(CompareProduct::class);
    }
    
    /**
     * Get the recently viewed products for the user.
     */
    public function recentlyViewedProducts()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }
    
    /**
     * Get the OTP codes for the user.
     */
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}