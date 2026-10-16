<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyAccount extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $fillable = ['user_id', 'balance_points'];

    protected $casts = [
        'balance_points' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}