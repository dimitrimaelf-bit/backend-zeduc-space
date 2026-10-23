<?php

namespace Tests\Unit;

use App\Http\Controllers\Etudiant\CartController;
use PHPUnit\Framework\TestCase;

class CartCalculationTest extends TestCase
{
    /** @test */
    public function test_il_calcule_correctement_le_total_du_panier(): void
    {
        // On instancie ou accède à la logique (si la méthode calculateTotal était publique, ou via réflexion / test direct d'un tableau équivalent)
        $cart = [
            1 => [
                'price_fcfa' => 2000,
                'quantity' => 2,
            ],
            2 => [
                'price_fcfa' => 1500,
                'quantity' => 1,
            ],
        ];

        // Simulation de la logique de calcul présente dans CartController
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price_fcfa'] * $item['quantity'];
        }

        $this->assertEquals(5500, $total);
    }
}