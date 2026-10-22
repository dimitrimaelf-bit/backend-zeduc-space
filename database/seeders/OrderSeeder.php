<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $roleEtudiant = Role::where('code', 'student')->first();

        // 1. Création des étudiants (Marc, Marie et un troisième)
        $etudiantsData = [
            ['first_name' => 'Marc', 'last_name' => 'Test', 'email' => 'marc@test.com', 'phone' => '0600000001'],
            ['first_name' => 'Marie', 'last_name' => 'Test', 'email' => 'marie@test.com', 'phone' => '0600000002'],
            ['first_name' => 'Client', 'last_name' => 'Test 3', 'email' => 'client3@test.com', 'phone' => '0600000003'],
        ];

        $users = [];
        foreach ($etudiantsData as $data) {
            $users[] = User::firstOrCreate(
                ['email' => $data['email']], // Recherche par email pour éviter les doublons
                [
                    'role_id' => $roleEtudiant->id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'password' => bcrypt('password'),
                    'is_active' => true
                ]
            );
        }

        // 2. Commandes pour aujourd'hui (associées à Marc / $users[0])
        Order::create([
            'order_number' => 'CMD-' . Str::random(6),
            'user_id' => $users[0]->id,
            'service_mode' => 'on_site',
            'subtotal_fcfa' => 5000,
            'total_fcfa' => 5000,
            'status' => 'completed',
            'created_at' => Carbon::now()
        ]);
        
        Order::create([
            'order_number' => 'CMD-' . Str::random(6),
            'user_id' => $users[0]->id,
            'service_mode' => 'delivery',
            'subtotal_fcfa' => 3000,
            'total_fcfa' => 3000,
            'status' => 'completed',
            'created_at' => Carbon::now()
        ]);
        
        // 3. Commandes d'il y a 3 jours (associées à Marie / $users[1])
        Order::create([
            'order_number' => 'CMD-' . Str::random(6),
            'user_id' => $users[1]->id,
            'service_mode' => 'on_site',
            'subtotal_fcfa' => 8000,
            'total_fcfa' => 8000,
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(3)
        ]);
        
        // 4. Commandes d'il y a 20 jours (associées au 3e utilisateur / $users[2])
        Order::create([
            'order_number' => 'CMD-' . Str::random(6),
            'user_id' => $users[2]->id,
            'service_mode' => 'delivery',
            'subtotal_fcfa' => 15000,
            'total_fcfa' => 15000,
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(20)
        ]);
    }
}