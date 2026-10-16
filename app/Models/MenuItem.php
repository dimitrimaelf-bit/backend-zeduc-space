<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'price_fcfa', 
        'image_url', 'is_available', 'is_daily_special'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_daily_special' => 'boolean',
        'price_fcfa' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }
}