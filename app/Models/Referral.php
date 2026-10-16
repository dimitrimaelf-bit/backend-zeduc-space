<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    const CREATED_AT = "created_at";
    const UPDATED_AT = null; // Seulement 'created_at'

    protected $fillable = [
        'referrer_user_id', 'referred_user_id', 'code_id', 
        'status', 'rewarded_at'
    ];

    protected $casts = [
        'rewarded_at' => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function code(): BelongsTo
    {
        return $this->belongsTo(ReferralCode::class, 'code_id');
    }
}