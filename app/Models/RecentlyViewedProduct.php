<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentlyViewedProduct extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
    ];
    
    /**
     * Get the user that owns the recently viewed product item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the product for the recently viewed product item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}