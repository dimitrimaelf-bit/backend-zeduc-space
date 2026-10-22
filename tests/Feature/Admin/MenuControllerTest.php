<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\MenuCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['id' => 1, 'code' => 'etudiant', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'gerant', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);

        $this->admin = User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'System',
            'email'         => 'admin@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 4,
            'is_active'     => true,
        ]);
    }

    #[Test]
    public function un_admin_peut_consulter_la_gestion_du_menu()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.menu.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function un_admin_peut_creer_un_article_avec_les_champs_du_schema()
    {
        $category = MenuCategory::create([
            'name' => 'Plats',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.menu.store'), [
                'category_id' => $category->id,
                'name' => 'Poulet braisé',
                'description' => 'Portion individuelle',
                'price_fcfa' => 4500,
                'is_available' => '1',
                'is_daily_special' => '1',
            ])
            ->assertRedirect(route('admin.menu.index'));

        $this->assertDatabaseHas('menu_items', [
            'name' => 'Poulet braisé',
            'category_id' => $category->id,
            'price_fcfa' => 4500,
            'is_available' => 1,
            'is_daily_special' => 1,
        ]);
    }
}