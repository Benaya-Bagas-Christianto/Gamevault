<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'total_amount',
        'payment_type',
        'payment_token',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getSnapUrlAttribute()
    {
        if (!$this->payment_token) return null;
        
        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v2/vtweb/' 
            : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/';
            
        return $baseUrl . $this->payment_token;
    }
}
