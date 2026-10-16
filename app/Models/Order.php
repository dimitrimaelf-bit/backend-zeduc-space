<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'service_mode', 'status', 
        'delivery_address', 'arrival_at', 'customer_note',
        'subtotal_fcfa', 'discount_fcfa', 'delivery_fee_fcfa', 
        'total_fcfa', 'loyalty_points_used', 'loyalty_points_earned',
        'completed_at'
    ];

    protected $casts = [
        'arrival_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}