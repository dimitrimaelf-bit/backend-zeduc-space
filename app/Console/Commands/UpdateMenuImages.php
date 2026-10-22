<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MenuItem;

class UpdateMenuImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:update-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour les images des plats et boissons avec les images téléchargées';

    /**
     * Execute the console command.
     */
    public function handle()
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
                $this->info("✓ Image mise à jour pour : {$item->name} -> {$imagePath}");
                $updatedCount++;
            } else {
                $this->warn("✗ Item non trouvé : {$itemName}");
            }
        }

        $this->info("\n✅ Total des images mises à jour : {$updatedCount}");
        
        return Command::SUCCESS;
    }
}
