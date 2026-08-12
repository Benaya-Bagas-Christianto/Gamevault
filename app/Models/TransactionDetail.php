<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'game_id',
        'price',
        'license_key',
        'refund_status',
        'refund_requested_at',
        'refund_processed_at',
        'refund_reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'refund_requested_at' => 'datetime',
        'refund_processed_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function library()
    {
        return $this->hasOne(Library::class);
    }

    public function canRefund(): bool
    {
        $transaction = $this->transaction;
        if (! $transaction || $transaction->status !== 'paid') return false;
        if ($this->refund_status !== 'none') return false;
        if (! $transaction->paid_at) return false;
        return $transaction->paid_at->diffInDays(now()) < 14;
    }
}
