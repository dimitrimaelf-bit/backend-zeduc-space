<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class UpdateMenuImagesSeeder extends Seeder
{
    /**
     * Met à jour les images des plats et boissons avec les images téléchargées
     */
    public function run(): void
    {
        // Association des noms d'items avec les chemins d'images correspondants
        $imageMapping = [
            // Plats
            'Poulet DG' => 'images/Etudiant/Poulet DG.jpg',
            'Poulet DG Spécial' => 'images/Etudiant/Poulet DG.jpg',
            'Riz Cantonais' => 'images/Etudiant/Riz Cantonais.jpeg',
            'Salade Composé' => 'images/Etudiant/Salade Composé.jpg',
            'Salade Composée' => 'images/Etudiant/Salade Composé.jpg',
            
            // Boissons
            'Bissap' => 'images/Etudiant/Bissap.jpg',
            'Bissap maison' => 'images/Etudiant/Bissap.jpg',
            'Eau minérale' => 'images/Etudiant/Eau minérale.jpg',
            'Coca Cola' => 'images/Etudiant/coca cola.jpg',
            'Coca-Cola' => 'images/Etudiant/coca cola.jpg',
            'Hero' => 'images/Etudiant/hero.jpeg',
            'Jus de fruits' => 'images/Etudiant/jus de fruits.jpg',
            'Jus' => 'images/Etudiant/jus de fruits.jpg',
        ];

        $updatedCount = 0;

        foreach ($imageMapping as $itemName => $imagePath) {
            // Recherche de l'item par nom (insensible à la casse et accents)
            $item = MenuItem::where('name', 'like', $itemName)
                ->orWhere('name', 'like', '%' . $itemName . '%')
                ->first();

            if ($item) {
                $item->update(['image_url' => $imagePath]);
                $this->command->info("✓ Image mise à jour pour : {$item->name} -> {$imagePath}");
                $updatedCount++;
            } else {
                $this->command->warn("✗ Item non trouvé : {$itemName}");
            }
        }

        $this->command->info("\nTotal des images mises à jour : {$updatedCount}");
    }
}
