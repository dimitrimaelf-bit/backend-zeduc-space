<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable

{
    use HasFactory, Notifiable; 

    protected $fillable = [
        'role_id', 'first_name', 'last_name', 'email', 
        'phone', 'password', 'delivery_address', 'is_active'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // Relations
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function loyaltyAccount(): HasOne
    {
        return $this->hasOne(LoyaltyAccount::class);
    }

    public function referralCode(): HasOne
    {
        return $this->hasOne(ReferralCode::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    // Références (parrainages)
    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_user_id');
    }

    public function referralReceived(): HasOne
    {
        return $this->hasOne(Referral::class, 'referred_user_id');
    }
}