<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class PlatSeeder extends Seeder
{
    public function run(): void
    {
        // 1. On s'assure qu'une catégorie "Plats Principaux" existe (ou on la récupère)
        $catPlats = MenuCategory::firstOrCreate([
            'name' => 'Plats Principaux'
        ], [
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 2. On insère le plat en activant explicitement le "Plat du jour"
        MenuItem::create([
            'category_id'      => $catPlats->id,
            'name'             => 'Poulet DG Spécial',
            'description'      => 'Un délicieux poulet DG bien chaud pour les étudiants.',
            'price_fcfa'       => 2500,
            'image_url'        => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1',
            'is_available'     => true,
            'is_daily_special' => true, // C'est cette ligne qui l'envoie directement dans les Plats du jour
        ]);
    }
}