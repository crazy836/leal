<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'status',
        'amount',
        'currency',
        'response_data',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'response_data' => 'array',
    ];
    
    /**
     * Get the order that owns the payment log.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}