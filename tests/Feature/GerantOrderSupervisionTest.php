<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantOrderSupervisionTest extends TestCase
{
    // Isole les tests en recréant la base après chaque scénario.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndSettingsSeeder::class);
    }

    // Vérifie le filtre par état et les compteurs globaux renvoyés au gérant.
    public function test_manager_can_filter_orders_and_view_live_status_totals(): void
    {
        // Prépare des commandes dans deux états pour distinguer filtre et totaux.
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $this->createOrder($customer, 'pending');
        $this->createOrder($customer, 'preparing');

        // Vérifie la liste filtrée et le résumé transmis à la vue Blade.
        $this->actingAs($manager)
            ->get(route('gerant.orders.index', ['status' => 'pending']))
            ->assertOk()
            ->assertViewIs('Gerant.Supervision')
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 1)
            ->assertViewHas('summary', fn ($summary) => $summary['pending'] === 1 && $summary['preparing'] === 1);
    }

    // Vérifie que les commandes ne sont pas visibles par un utilisateur non gérant.
    public function test_non_manager_cannot_access_order_supervision(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->get(route('gerant.orders.index'))
            ->assertForbidden();
    }

    public function test_legacy_manager_page_urls_redirect_to_current_pages(): void
    {
        $manager = $this->userWithRole('manager');

        $this->actingAs($manager)
            ->get('/Gerant/Supervision/')
            ->assertRedirect(route('gerant.orders.index'));

        $this->get('/Gerant/Supervision/Orderspending')
            ->assertRedirect(route('gerant.orders.index', ['status' => 'preparing']));

        $this->get('/Gerant/Supervision/Ordersready')
            ->assertRedirect(route('gerant.orders.index', ['status' => 'ready']));

        $this->get('/Gerant/Supervision/Ordersdelivered')
            ->assertRedirect(route('gerant.orders.index', ['status' => 'delivered']));

        $this->get('/Gerant/Claims')
            ->assertRedirect(route('gerant.claims.index'));

        $this->get('/Gerant/Employeeaccounts')
            ->assertRedirect(route('gerant.employees.index'));

        $this->get('/Gerant/Stats')
            ->assertRedirect(route('gerant.statistics.index'));
    }

    // Vérifie qu'un gérant peut modifier l'état d'une commande.
    public function test_manager_can_change_order_status(): void
    {
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $order = $this->createOrder($customer, 'pending');

        $this->actingAs($manager)
            ->patch(route('gerant.orders.status.update', $order), ['status' => 'preparing'])
            ->assertRedirect(route('gerant.orders.index'))
            ->assertSessionHas('status', 'Le statut de la commande a été mis à jour.');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'preparing',
        ]);
    }

    // Vérifie qu'un utilisateur non gérant ne peut pas modifier une commande.
    public function test_non_manager_cannot_change_order_status(): void
    {
        $employee = $this->userWithRole('employee');
        $customer = $this->userWithRole('student');
        $order = $this->createOrder($customer, 'pending');

        $this->actingAs($employee)
            ->patch(route('gerant.orders.status.update', $order), ['status' => 'ready'])
            ->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    // Vérifie qu'une valeur d'état non autorisée est rejetée et ne modifie pas la commande.
    public function test_manager_cannot_set_an_unsupported_order_status(): void
    {
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $order = $this->createOrder($customer, 'pending');

        $this->actingAs($manager)
            ->patch(route('gerant.orders.status.update', $order), ['status' => 'unknown'])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    // Crée un utilisateur rattaché au rôle utilisé dans le scénario.
    private function userWithRole(string $roleCode): User
    {
        // Garantit que le rôle existe avant la création du compte.
        $role = Role::firstOrCreate(
            ['code' => $roleCode],
            ['label' => ucfirst($roleCode)]
        );

        // Retourne un utilisateur avec une adresse dédiée au rôle.
        return User::create([
            'role_id' => $role->id,
            'first_name' => ucfirst($roleCode),
            'last_name' => 'Test',
            'email' => $roleCode.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }

    // Crée une commande de test associée au client fourni.
    private function createOrder(User $customer, string $status): Order
    {
        // Rend le numéro de commande distinct même si plusieurs tests utilisent le même état.
        return Order::create([
            'order_number' => strtoupper($status).'-'.uniqid(),
            'user_id' => $customer->id,
            'service_mode' => 'on_site',
            'status' => $status,
        ]);
    }
}