<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppSetting extends Model
{
    protected $primaryKey = 'setting_key';
    public $incrementing = false;
    const CREATED_AT = null;
    const UPDATED_AT = "updated_at";

    protected $fillable = ['setting_key', 'setting_value', 'updated_by'];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}