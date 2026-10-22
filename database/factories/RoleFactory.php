<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->randomElement(['student', 'admin', 'employee', 'manager']),
            'label' => fake()->jobTitle(),
        ];
    }

    public function student(): static
    {
        return $this->state(fn () => [
            'code' => 'student',
            'label' => 'Etudiant',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'code' => 'admin',
            'label' => 'Administrateur',
        ]);
    }

    public function employee(): static
    {
        return $this->state(fn () => [
            'code' => 'employee',
            'label' => 'Employé',
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn () => [
            'code' => 'manager',
            'label' => 'Gérant',
        ]);
    }
}