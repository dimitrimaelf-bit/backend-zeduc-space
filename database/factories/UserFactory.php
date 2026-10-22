<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Le mot de passe actuel utilisé par la factory.
     */
    protected static ?string $password;

    /**
     * Définit l'état par défaut du modèle (adapté à la migration users).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Récupère ou crée le rôle étudiant par défaut pour éviter les erreurs de clé étrangère
        $role = Role::firstOrCreate(
            ['code' => 'student'],
            ['label' => 'Etudiant']
        );

        return [
            'role_id'          => $role->id,
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->unique()->safeEmail(),
            'phone'            => fake()->phoneNumber(),
            'password'         => static::$password ??= Hash::make('password'),
            'delivery_address' => fake()->address(),
            'is_active'        => true,
        ];
    }
}