<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    // Réutilise le rôle s'il existe déjà, sinon le crée (évite les doublons sur roles.code)
    private function roleId(string $code, string $label): int
    {
        return Role::firstOrCreate(['code' => $code], ['label' => $label])->id;
    }

    public function definition(): array
    {
        return [
            'role_id' => fn () => $this->roleId('student', 'Etudiant'),
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
        return $this->state(fn () => ['role_id' => $this->roleId('admin', 'Administrateur')]);
    }

    public function employee(): static
    {
        return $this->state(fn () => ['role_id' => $this->roleId('employee', 'Employé')]);
    }

    public function student(): static
    {
        return $this->state(fn () => ['role_id' => $this->roleId('student', 'Etudiant')]);
    }

    public function manager(): static
    {
        return $this->state(fn () => ['role_id' => $this->roleId('manager', 'Gérant')]);
    }
}