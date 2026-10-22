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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_sales_order_loyalty_and_referral_statistics(): void
    {
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $order = Order::create([
            'order_number' => 'ORDER-STAT-1',
            'user_id' => $customer->id,
            'service_mode' => 'on_site',
            'status' => 'completed',
            'total_fcfa' => 2500,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'provider' => 'test',
            'method' => 'cash',
            'status' => 'paid',
            'amount_fcfa' => 2500,
        ]);

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

        $this->actingAs($manager)
            ->getJson(route('gerant.statistics.index'))
            ->assertOk()
            ->assertJsonPath('sales.paid_total_fcfa', 2500)
            ->assertJsonPath('sales.paid_transactions', 1)
            ->assertJsonPath('orders.total', 1)
            ->assertJsonPath('orders.by_status.completed', 1)
            ->assertJsonPath('loyalty.accounts', 1)
            ->assertJsonPath('loyalty.balance_points_total', 12)
            ->assertJsonPath('loyalty.by_type.earn.points_total', 12)
            ->assertJsonPath('referrals.active_codes', 1)
            ->assertJsonPath('referrals.by_status.rewarded', 1)
            ->assertJsonStructure(['refreshed_at']);
    }

    public function test_non_manager_cannot_view_statistics(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->getJson(route('gerant.statistics.index'))
            ->assertForbidden();
    }

    private function userWithRole(string $roleCode): User
    {
        $role = Role::firstOrCreate(
            ['code' => $roleCode],
            ['label' => ucfirst($roleCode)]
        );

        return User::create([
            'role_id' => $role->id,
            'first_name' => ucfirst($roleCode),
            'last_name' => 'Test',
            'email' => $roleCode.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}