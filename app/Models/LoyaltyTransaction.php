<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    const CREATED_AT = "created_at";
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'order_id', 'type', 'points', 
        'description', 'expires_at', 'source_transaction_id'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}