<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class AddMissingMenuItemsSeeder extends Seeder
{
    /**
     * Ajoute les items de menu manquants avec leurs images
     */
    public function run(): void
    {
        // Récupérer ou créer la catégorie Boissons
        $catBoissons = MenuCategory::firstOrCreate([
            'name' => 'Boissons'
        ], [
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Items à ajouter avec leurs images
        $itemsToAdd = [
            [
                'name' => 'Coca Cola',
                'description' => 'Coca Cola rafraîchissant',
                'price_fcfa' => 500,
                'image_url' => 'images/Etudiant/coca cola.jpg',
            ],
            [
                'name' => 'Hero',
                'description' => 'Boisson énergisante Hero',
                'price_fcfa' => 600,
                'image_url' => 'images/Etudiant/hero.jpeg',
            ],
            [
                'name' => 'Jus de fruits',
                'description' => 'Jus de fruits naturel',
                'price_fcfa' => 700,
                'image_url' => 'images/Etudiant/jus de fruits.jpg',
            ],
        ];

        $addedCount = 0;

        foreach ($itemsToAdd as $itemData) {
            // Vérifier si l'item existe déjà
            $existing = MenuItem::where('name', $itemData['name'])->first();

            if (!$existing) {
                MenuItem::create([
                    'category_id' => $catBoissons->id,
                    'name' => $itemData['name'],
                    'description' => $itemData['description'],
                    'price_fcfa' => $itemData['price_fcfa'],
                    'image_url' => $itemData['image_url'],
                    'is_available' => true,
                    'is_daily_special' => false,
                ]);
                $this->command->info("✓ Item ajouté : {$itemData['name']}");
                $addedCount++;
            } else {
                // Mettre à jour l'image si l'item existe déjà
                $existing->update(['image_url' => $itemData['image_url']]);
                $this->command->info("✓ Image mise à jour pour : {$itemData['name']}");
                $addedCount++;
            }
        }

        $this->command->info("\n✅ Total des items traités : {$addedCount}");
    }
}
