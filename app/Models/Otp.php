<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'is_used'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}