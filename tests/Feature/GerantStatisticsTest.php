<?php

namespace Tests\Feature;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantStatisticsTest extends TestCase
{
    // Garantit que chaque test démarre avec une base de données propre.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndSettingsSeeder::class);
    }

    // Vérifie le calcul et la présentation des indicateurs de vente, fidélité et parrainage.
    public function test_manager_can_view_sales_order_loyalty_and_referral_statistics(): void
    {
        // Prépare un gérant, un client et une commande payée.
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $order = Order::create([
            'order_number' => 'ORDER-STAT-1',
            'user_id' => $customer->id,
            'service_mode' => 'on_site',
            'status' => 'completed',
            'total_fcfa' => 2500,
        ]);

        // Associe un paiement encaissé à la commande pour alimenter les chiffres de vente.
        Payment::create([
            'order_id' => $order->id,
            'provider' => 'test',
            'method' => 'cash',
            'status' => 'paid',
            'amount_fcfa' => 2500,
        ]);

        // Ajoute un compte et une transaction de fidélité afin de vérifier le solde et les points gagnés.
        LoyaltyAccount::create([
            'user_id' => $customer->id,
            'balance_points' => 12,
        ]);
        LoyaltyTransaction::create([
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'type' => 'earn',
            'points' => 12,
            'description' => 'Points gagnés sur une commande',
        ]);

        // Prépare un code de parrainage actif et un parrainage récompensé.
        $referralCode = ReferralCode::create([
            'user_id' => $customer->id,
            'code' => 'REF-STAT-1',
            'is_active' => true,
        ]);
        Referral::create([
            'referrer_user_id' => $customer->id,
            'referred_user_id' => $this->userWithRole('student2')->id,
            'code_id' => $referralCode->id,
            'status' => 'rewarded',
        ]);

        // Contrôle les indicateurs calculés pour chaque domaine statistique.
        $this->actingAs($manager)
            ->get(route('gerant.statistics.index'))
            ->assertOk()
            ->assertViewIs('Gerant.Stats')
            ->assertViewHas('sales', fn ($sales) => $sales['paid_total_fcfa'] === 2500 && $sales['paid_transactions'] === 1)
            ->assertViewHas('orders', fn ($orders) => $orders['total'] === 1 && $orders['by_status']['completed'] === 1)
            ->assertViewHas('loyalty', fn ($loyalty) => $loyalty['accounts'] === 1 && $loyalty['balance_points_total'] === 12 && $loyalty['by_type']['earn']['points_total'] === 12)
            ->assertViewHas('referrals', fn ($referrals) => $referrals['active_codes'] === 1 && $referrals['by_status']['rewarded'] === 1);
    }

    // Vérifie que les statistiques ne sont accessibles qu'aux gérants.
    public function test_non_manager_cannot_view_statistics(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->get(route('gerant.statistics.index'))
            ->assertForbidden();
    }

    // Crée un utilisateur de test ayant le rôle demandé.
    private function userWithRole(string $roleCode): User
    {
        // Ajoute le rôle dans la base s'il n'y est pas encore.
        $role = Role::firstOrCreate(
            ['code' => $roleCode],
            ['label' => ucfirst($roleCode)]
        );

        // Enregistre puis retourne un utilisateur de ce rôle.
        return User::create([
            'role_id' => $role->id,
            'first_name' => ucfirst($roleCode),
            'last_name' => 'Test',
            'email' => $roleCode.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}