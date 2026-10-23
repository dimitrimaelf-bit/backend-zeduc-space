<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\LoyaltyAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class EtudiantWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['code' => 'student'], ['label' => 'Etudiant']);

        $this->student = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        LoyaltyAccount::create([
            'user_id' => $this->student->id,
            'balance_points' => 50,
        ]);
    }

    public function test_un_etudiant_peut_acceder_a_sa_page_accueil(): void
    {
        $response = $this->actingAs($this->student)->get(route('etudiant.accueil'));

        $response->assertStatus(200);
        $response->assertViewIs('etudiant.accueil');
        $response->assertViewHas(['user', 'balance', 'dailySpecials', 'promotions', 'topClients']);
    }

    public function test_un_etudiant_peut_ajouter_un_plat_disponible_au_panier(): void
    {
        $category = MenuCategory::create(['name' => 'Plats', 'sort_order' => 1, 'is_active' => true]);
        $item = MenuItem::create([
            'category_id' => $category->id,
            'name' => 'Poulet DG',
            'description' => 'Délicieux poulet',
            'price_fcfa' => 2500,
            'is_available' => true,
        ]);

        $response = $this->actingAs($this->student)->post(route('etudiant.cart.add'), [
            'menu_item_id' => $item->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertTrue(Session::has('cart'));
        $this->assertEquals(2, Session::get('cart')[$item->id]['quantity']);
    }

    public function test_un_etudiant_peut_valider_une_commande_depuis_son_panier(): void
    {
        $category = MenuCategory::create(['name' => 'Boissons', 'sort_order' => 2, 'is_active' => true]);
        $item = MenuItem::create([
            'category_id' => $category->id,
            'name' => 'Jus naturel',
            'description' => 'Frais',
            'price_fcfa' => 1000,
            'is_available' => true,
        ]);

        Session::put('cart', [
            $item->id => [
                'id' => $item->id,
                'name' => $item->name,
                'price_fcfa' => $item->price_fcfa,
                'quantity' => 2,
                'image_url' => null,
            ]
        ]);

        $response = $this->actingAs($this->student)->post(route('etudiant.order.store'), [
            'service_mode' => 'on_site',
        ]);

        $response->assertRedirect(route('etudiant.menu.index'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->student->id,
            'payment_method' => 'cash',
            'service_mode' => 'on_site',
            'total_fcfa' => 2000,
            'status' => 'pending',
        ]);

        $this->assertFalse(Session::has('cart'));
    }

    public function test_un_etudiant_peut_generer_un_code_de_parrainage(): void
    {
        $response = $this->actingAs($this->student)->post(route('etudiant.parrainage.generer'));

        $response->assertRedirect();
        $this->assertDatabaseHas('referral_codes', [
            'user_id' => $this->student->id,
            'is_active' => true,
        ]);
    }
}