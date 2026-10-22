<?php

namespace Database\Factories;

use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Referral>
 */
class ReferralFactory extends Factory
{
    protected $model = Referral::class;

    public function definition(): array
    {
        $referrer = User::factory()->student();

        return [
            'referrer_user_id' => $referrer,
            'referred_user_id' => User::factory()->student(),
            'code_id' => ReferralCode::factory()->state(fn (array $attrs) => [
                'user_id' => $attrs['referrer_user_id'],
            ]),
        ];
    }
}