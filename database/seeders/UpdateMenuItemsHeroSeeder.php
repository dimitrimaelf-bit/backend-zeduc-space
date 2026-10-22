<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class UpdateMenuItemsHeroSeeder extends Seeder
{
    /**
     * Supprime Hero des boissons et ajoute/mettre à jour Ero et Waterfufu avec l'image hero
     */
    public function run(): void
    {
        // 1. Supprimer l'item "Hero" de la catégorie Boissons
        $heroDrink = MenuItem::where('name', 'like', '%Hero%')
            ->whereHas('category', function($query) {
                $query->where('name', 'like', '%Boisson%');
            })
            ->first();

        if ($heroDrink) {
            $heroDrink->delete();
            $this->command->info("✓ Item 'Hero' supprimé des boissons");
        } else {
            $this->command->warn("✗ Item 'Hero' non trouvé dans les boissons");
        }

        // 2. Récupérer ou créer la catégorie Plats Principaux
        $catPlats = MenuCategory::firstOrCreate([
            'name' => 'Plats Principaux'
        ], [
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 3. Ajouter ou mettre à jour le plat "Ero" avec l'image hero
        $ero = MenuItem::where('name', 'like', '%Ero%')->first();

        if ($ero) {
            $ero->update([
                'image_url' => 'images/Etudiant/hero.jpeg',
                'category_id' => $catPlats->id,
            ]);
            $this->command->info("✓ Plat 'Ero' mis à jour avec l'image hero");
        } else {
            MenuItem::create([
                'category_id' => $catPlats->id,
                'name' => 'Ero',
                'description' => 'Plat Ero spécial',
                'price_fcfa' => 3000,
                'image_url' => 'images/Etudiant/hero.jpeg',
                'is_available' => true,
                'is_daily_special' => false,
            ]);
            $this->command->info("✓ Plat 'Ero' ajouté avec l'image hero");
        }

        // 4. Ajouter ou mettre à jour le plat "Waterfufu" avec l'image hero
        $waterfufu = MenuItem::where('name', 'like', '%Waterfufu%')->first();

        if ($waterfufu) {
            $waterfufu->update([
                'image_url' => 'images/Etudiant/hero.jpeg',
                'category_id' => $catPlats->id,
            ]);
            $this->command->info("✓ Plat 'Waterfufu' mis à jour avec l'image hero");
        } else {
            MenuItem::create([
                'category_id' => $catPlats->id,
                'name' => 'Waterfufu',
                'description' => 'Plat Waterfufu délicieux',
                'price_fcfa' => 2500,
                'image_url' => 'images/Etudiant/hero.jpeg',
                'is_available' => true,
                'is_daily_special' => false,
            ]);
            $this->command->info("✓ Plat 'Waterfufu' ajouté avec l'image hero");
        }

        $this->command->info("\n✅ Mise à jour terminée : Hero supprimé des boissons, Ero et Waterfufu ajoutés/mis à jour");
    }
}
