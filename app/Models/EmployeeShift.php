<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeShift extends Model
{
    protected $fillable = [
        'user_id',
        'shift_date',
        'service',
        'started_at',
        'ended_at',
        'status',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDurationMinutesAttribute(): ?int
    {
        if ($this->started_at === null || $this->ended_at === null) {
            return null;
        }

        return (int) $this->started_at->diffInMinutes($this->ended_at);
    }
}
