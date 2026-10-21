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

       // 1. Créer 3 étudiants fictifs (sans créer de doublons s'ils existent déjà)
        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $users[] = User::firstOrCreate(
                ['email' => "client{$i}@test.com"], // Laravel cherche cet email
                [
                    'role_id' => $roleEtudiant->id,
                    'first_name' => 'Client',
                    'last_name' => 'Test ' . $i,
                    'phone' => "060000000{$i}",
                    'password' => bcrypt('password'),
                    'is_active' => true
                ] // Si l'email n'existe pas, il crée le compte avec ces infos
            );
        }

        // 2. Commandes pour aujourd'hui (Filtre "Jour")
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
        
        // 3. Commandes d'il y a 3 jours (Filtre "Semaine")
        Order::create([
            'order_number' => 'CMD-' . Str::random(6),
            'user_id' => $users[1]->id,
            'service_mode' => 'on_site',
            'subtotal_fcfa' => 8000,
            'total_fcfa' => 8000,
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(3)
        ]);
        
        // 4. Commandes d'il y a 20 jours (Filtre "Mois")
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