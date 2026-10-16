<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    const CREATED_AT = "created_at";
    const UPDATED_AT = null;

    protected $fillable = [
        'order_id', 'provider', 'provider_transaction_id', 
        'method', 'status', 'amount_fcfa', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}