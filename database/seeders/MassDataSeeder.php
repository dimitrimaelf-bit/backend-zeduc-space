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
        // 1. AJOUT MASSIF DE PROMOTIONS ET EVENEMENTS
        // ==========================================

        // Tableau contenant les promotions et evenements
        // - EN COURS AUJOURD'HUI : S'affichent a l'accueil ET sur la page Evenements
        // - PLANIFIES POUR LES JOURS SUIVANTS : Uniquement sur la page Evenements & Promotions
        $promotions = [
            // --- EN COURS AUJOURD'HUI (S'affichent a l'accueil ET sur la page Evenements) ---
            [
                'type'        => 'promotion',
                'title'       => 'Happy Hour',
                'description' => '-20% sur toutes les boissons fraiches de 14h a 16h.',
                'starts_at'   => now()->startOfDay(),
                'ends_at'     => now()->endOfDay(),
                'is_active'   => true,
            ],
            [
                'type'        => 'promotion',
                'title'       => 'Menu Duo Etudiant',
                'description' => '2 Plats achetes = 1 Boisson offerte immediatement.',
                'starts_at'   => now()->startOfDay(),
                'ends_at'     => now()->addDays(2)->endOfDay(),
                'is_active'   => true,
            ],
            [
                'type'        => 'event',
                'title'       => 'Quiz Musical du Midi',
                'description' => 'Viens tester ta culture musicale a la pause et gagne des points de fidelite !',
                'starts_at'   => now()->startOfDay(),
                'ends_at'     => now()->endOfDay(),
                'is_active'   => true,
            ],

            // --- PLANIFIES POUR LES JOURS SUIVANTS (Uniquement sur la page Evenements & Promotions) ---
            [
                'type'        => 'event',
                'title'       => 'Tournoi FIFA & Gaming Ucac-Icam',
                'description' => 'Grand tournoi sur ecran geant a l\'espace detente. Inscription gratuite, 50 points de fidelite et un menu complet pour le vainqueur !',
                'starts_at'   => now()->addDays(2)->setHour(15)->setMinute(0),
                'ends_at'     => now()->addDays(2)->setHour(19)->setMinute(0),
                'is_active'   => true,
            ],
            [
                'type'        => 'promotion',
                'title'       => 'Vendredi Livraison Gratuite',
                'description' => 'Frais de livraison offerts pour toute commande superieure a 5 000 F.',
                'starts_at'   => now()->addDays(3)->startOfDay(),
                'ends_at'     => now()->addDays(3)->endOfDay(),
                'is_active'   => true,
            ],
            [
                'type'        => 'event',
                'title'       => 'Soiree Karaoke & Grillades',
                'description' => 'Ambiance festive entre etudiants avec menu special grillades et boissons a prix reduit.',
                'starts_at'   => now()->addDays(5)->setHour(18)->setMinute(30),
                'ends_at'     => now()->addDays(5)->setHour(22)->setMinute(0),
                'is_active'   => true,
            ],
            [
                'type'        => 'promotion',
                'title'       => 'Semaine du Poulet DG',
                'description' => '-500 F de reduction immediate sur le Poulet DG toute la semaine prochaine.',
                'starts_at'   => now()->addDays(7)->startOfDay(),
                'ends_at'     => now()->addDays(12)->endOfDay(),
                'is_active'   => true,
            ],
        ];

        // Parcours du tableau des promotions et événements
        foreach ($promotions as $promo) {
            // updateOrCreate met à jour si existe déjà, sinon crée
            Promotion::updateOrCreate(
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