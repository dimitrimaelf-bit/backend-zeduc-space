<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Claim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
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
    public function un_non_admin_ne_peut_pas_acceder_au_dashboard()
    {
        $student = User::create([
            'first_name'    => 'Etudiant',
            'last_name'     => 'Test',
            'email'         => 'student@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 1,
            'is_active'     => true,
        ]);

        $response = $this->actingAs($student)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    #[Test]
    public function un_admin_peut_acceder_au_dashboard()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    #[Test]
    public function le_dashboard_affiche_les_ventes_payees_de_la_periode_selectionnee()
    {
        $paidOrder = Order::create([
            'order_number' => 'DASH-PAID-001',
            'user_id' => $this->admin->id,
            'service_mode' => 'on_site',
            'status' => 'completed',
            'total_fcfa' => 8500,
        ]);
        Payment::create([
            'order_id' => $paidOrder->id,
            'provider' => 'test',
            'provider_transaction_id' => 'dashboard-paid',
            'method' => 'test',
            'status' => 'paid',
            'amount_fcfa' => 8500,
            'paid_at' => now(),
        ]);

        $pendingOrder = Order::create([
            'order_number' => 'DASH-PENDING-001',
            'user_id' => $this->admin->id,
            'service_mode' => 'on_site',
            'status' => 'pending',
            'total_fcfa' => 12000,
        ]);
        Payment::create([
            'order_id' => $pendingOrder->id,
            'provider' => 'test',
            'provider_transaction_id' => 'dashboard-pending',
            'method' => 'test',
            'status' => 'pending',
            'amount_fcfa' => 12000,
        ]);
        Claim::create([
            'order_id' => $paidOrder->id,
            'user_id' => $this->admin->id,
            'subject' => 'Réclamation à traiter',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard', ['period' => 'week']));

        $response->assertOk()
            ->assertSee('8 500 FCFA')
            ->assertSee('Cette semaine')
            ->assertSee('Réclamations ouvertes')
            ->assertSee('DASH-PAID-001')
            ->assertSee('DASH-PENDING-001')
            ->assertViewHas('stats.pending_claims', 1);
    }

    #[Test]
    public function un_admin_peut_ouvrir_la_page_de_statistiques_detaillees()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.stats', ['period' => 'month']));

        $response->assertOk()
            ->assertSee('Statistiques détaillées')
            ->assertSee('Répartition des commandes')
            ->assertSee(route('admin.stats', ['period' => 'day']), false);
    }
}