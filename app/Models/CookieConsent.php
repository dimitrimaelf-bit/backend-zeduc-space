<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CookieConsent extends Model
{
    const CREATED_AT = "created_at";
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'session_id', 'consented', 'policy_version'
    ];

    protected $casts = [
        'consented' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}