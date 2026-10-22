<?php

namespace Tests\Feature\Admin;

use App\Models\AppSetting;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_confirmation_marks_payment_paid_and_awards_points_once(): void
    {
        [$admin, $student] = $this->createUsers();
        AppSetting::updateOrCreate(
            ['setting_key' => 'loyalty_points_expiry_months'],
            ['setting_value' => '12']
        );
        $order = Order::create([
            'order_number' => 'PAY-CASH-001',
            'user_id' => $student->id,
            'service_mode' => 'on_site',
            'status' => 'pending',
            'total_fcfa' => 3000,
            'loyalty_points_earned' => 3,
        ]);
        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'manual_cash',
            'method' => 'cash',
            'status' => 'pending',
            'amount_fcfa' => 3000,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.payments.confirm-cash', $payment))
            ->assertRedirect(route('admin.payments.index'));
        $this->actingAs($admin)
            ->post(route('admin.payments.confirm-cash', $payment))
            ->assertRedirect(route('admin.payments.index'));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);
        $this->assertSame(3, LoyaltyAccount::where('user_id', $student->id)->value('balance_points'));
        $this->assertSame(1, LoyaltyTransaction::where('order_id', $order->id)->where('type', 'earn')->count());
        $this->assertNotNull(LoyaltyTransaction::where('order_id', $order->id)->value('expires_at'));
    }

    public function test_student_cannot_confirm_a_cash_payment(): void
    {
        [, $student] = $this->createUsers();
        $order = Order::create([
            'order_number' => 'PAY-CASH-002',
            'user_id' => $student->id,
            'service_mode' => 'on_site',
            'status' => 'pending',
        ]);
        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'manual_cash',
            'method' => 'cash',
            'status' => 'pending',
            'amount_fcfa' => 1000,
        ]);

        $this->actingAs($student)
            ->post(route('admin.payments.confirm-cash', $payment))
            ->assertForbidden();

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'pending']);
    }

    private function createUsers(): array
    {
        $studentRole = Role::create(['id' => 1, 'code' => 'student', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'manager', 'label' => 'Gérant']);
        $adminRole = Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);

        $admin = User::create([
            'role_id' => $adminRole->id,
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin-payment@example.test',
            'password' => 'Password123!',
        ]);
        $student = User::create([
            'role_id' => $studentRole->id,
            'first_name' => 'Etudiant',
            'last_name' => 'Test',
            'email' => 'student-payment@example.test',
            'password' => 'Password123!',
        ]);

        return [$admin, $student];
    }
}
