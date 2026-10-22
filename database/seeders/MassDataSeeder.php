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
        // ==========================================
        // 1. AJOUT MASSIF DE PROMOTIONS
        // ==========================================
        
        // Tableau contenant les différentes offres promotionnelles à insérer
        $promotions = [
            ['title' => 'Happy Hour', 'description' => '-20% sur les boissons de 14h à 16h', 'is_active' => true],
            ['title' => 'Menu Duo', 'description' => '2 Plats achetés = 1 Boisson offerte', 'is_active' => true],
            ['title' => 'Livraison Gratuite', 'description' => 'Pour toute commande de plus de 5000 F', 'is_active' => true],
        ];

        // Parcours du tableau des promotions
        foreach ($promotions as $promo) {
            // firstOrCreate vérifie si la promotion existe déjà grâce au titre.
            // Si elle existe, il ne fait rien ; sinon, il l'insère en base de données.
            Promotion::firstOrCreate(
                ['title' => $promo['title']], 
                $promo
            );
        }

        // ==========================================
        // 2. AJOUT DE LA CATÉGORIE ET DES PLATS DU JOUR
        // ==========================================
        
        // Récupère la catégorie "Plats Principaux" si elle existe déjà, 
        // ou la crée si elle n'existe pas encore (évite les doublons de catégories).
        $categorie = MenuCategory::firstOrCreate(
            ['name' => 'Plats Principaux'], 
            ['sort_order' => 1, 'is_active' => true]
        );

        // Tableau contenant les plats du jour à proposer
        $platsDuJour = [
            ['name' => 'Poulet DG', 'description' => 'Poulet frit et plantains', 'price_fcfa' => 2500],
            ['name' => 'Riz Cantonais', 'description' => 'Riz sauté aux légumes', 'price_fcfa' => 2000],
            ['name' => 'Ero et Waterfufu', 'description' => 'Spécialité du Sud-Ouest', 'price_fcfa' => 3000],
            ['name' => 'Salade composée', 'description' => 'Légumes frais de saison', 'price_fcfa' => 1500],
        ];

        // Parcours de chaque plat du jour pour l'insérer en base
        foreach ($platsDuJour as $plat) {
            // firstOrCreate vérifie si le plat existe déjà par son nom.
            MenuItem::firstOrCreate(
                ['name' => $plat['name']], 
                [
                    'category_id'      => $categorie->id, // Associe le plat à la catégorie "Plats Principaux"
                    'description'      => $plat['description'],
                    'price_fcfa'       => $plat['price_fcfa'],
                    'is_available'     => true,           // Le plat est disponible à la commande
                    'is_daily_special' => true,           // Marque le plat comme "Plat du jour"
                ]
            );
        }

        // ==========================================
        // 3. CRÉATION DE LA CATÉGORIE BOISSONS ET AJOUT DES BOISSONS
        // ==========================================
        
        // Crée la catégorie "Boissons" de manière unique (ordre de tri 2)
        $catBoissons = MenuCategory::firstOrCreate(
            ['name' => 'Boissons'], 
            ['sort_order' => 2, 'is_active' => true]
        );

        // Tableau contenant la liste des boissons à insérer
        $boissons = [
            ['name' => 'Jus de fruit naturel', 'description' => 'Frais et pressé du jour', 'price_fcfa' => 1000],
            ['name' => 'Coca-Cola 33cl', 'description' => 'Boisson gazeuse bien fraîche', 'price_fcfa' => 600],
            ['name' => 'Eau minérale 1.5L', 'description' => 'Eau plate naturelle', 'price_fcfa' => 500],
            ['name' => 'Bissap maison', 'description' => 'Jus d’hibiscus traditionnel rafraîchissant', 'price_fcfa' => 700],
        ];

        // Parcours de chaque boisson pour l'enregistrer proprement
        foreach ($boissons as $boisson) {
            MenuItem::firstOrCreate(
                ['name' => $boisson['name']], // Vérifie par le nom pour éviter les doublons
                [
                    'category_id'      => $catBoissons->id, // Associe la boisson à la catégorie "Boissons"
                    'description'      => $boisson['description'],
                    'price_fcfa'       => $boisson['price_fcfa'],
                    'is_available'     => true,             // La boisson est disponible
                    'is_daily_special' => false,          // Ce n'est pas un plat du jour
                ]
            );
        }
    }
}