<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // seed the application's database
    public function run(): void
    {
        $this->call([
            RolesAndSettingsSeeder::class,
            GerantUserSeeder::class,
            MassDataSeeder::class,
            OrderSeeder::class,
            UpdateMenuImagesSeeder::class, // Met à jour les images des plats et boissons
        ]);
    }
}