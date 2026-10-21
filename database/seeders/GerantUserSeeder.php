<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class GerantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'naomi.poutougnigni@2030.ucac-icam.com'], // Condition d'unicité
            [
                'first_name' => 'Aurelie',
                'last_name'  => 'Poutougnigni',
                'role_id'  => 3, // Rôle Gérant
                'password' => Hash::make('password123'), // Chiffrement du mot de passe
            ]
        );
    }
}
