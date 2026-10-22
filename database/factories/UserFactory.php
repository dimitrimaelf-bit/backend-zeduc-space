<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => Role::factory()->student(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'delivery_address' => fake()->optional()->address(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::factory()->admin(),
        ]);
    }

    public function employee(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::factory()->employee(),
        ]);
    }

    public function student(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::factory()->student(),
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::factory()->manager(),
        ]);
    }
}