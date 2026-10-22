<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    const CREATED_AT = "created_at";
    const UPDATED_AT = null; // Seulement 'created_at'

    protected $fillable = [
        'title', 'description', 'image_url', 
        'starts_at', 'ends_at', 'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}