<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;

class MassDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. AJOUT MASSIF DE PROMOTIONS ---
        $promotions = [
            ['title' => 'Happy Hour', 'description' => '-20% sur les boissons de 14h à 16h', 'is_active' => true],
            ['title' => 'Menu Duo', 'description' => '2 Plats achetés = 1 Boisson offerte', 'is_active' => true],
            ['title' => 'Livraison Gratuite', 'description' => 'Pour toute commande de plus de 5000 F', 'is_active' => true],
        ];

        foreach ($promotions as $promo) {
            Promotion::create($promo);
        }

        // --- 2. AJOUT MASSIF DE PLATS DU JOUR ---
        $categorie = MenuCategory::firstOrCreate(
            ['name' => 'Plats Principaux'], 
            ['sort_order' => 1, 'is_active' => true]
        );

        $platsDuJour = [
            ['name' => 'Poulet DG', 'description' => 'Poulet frit et plantains', 'price_fcfa' => 2500],
            ['name' => 'Riz Cantonais', 'description' => 'Riz sauté aux légumes', 'price_fcfa' => 2000],
            ['name' => 'Ero et Waterfufu', 'description' => 'Spécialité du Sud-Ouest', 'price_fcfa' => 3000],
            ['name' => 'Salade composée', 'description' => 'Légumes frais de saison', 'price_fcfa' => 1500],
        ];

        foreach ($platsDuJour as $plat) {
            MenuItem::create([
                'category_id'      => $categorie->id,
                'name'             => $plat['name'],
                'description'      => $plat['description'],
                'price_fcfa'       => $plat['price_fcfa'],
                'is_available'     => true,
                'is_daily_special' => true, // Apparaîtront tous dans la section "Plats du jour"
            ]);
        }
    }
}