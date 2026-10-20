<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantOrderSupervisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_filter_orders_and_view_live_status_totals(): void
    {
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $this->createOrder($customer, 'pending');
        $this->createOrder($customer, 'preparing');

        $this->actingAs($manager)
            ->getJson(route('gerant.orders.index', ['status' => 'pending']))
            ->assertOk()
            ->assertJsonPath('orders.total', 1)
            ->assertJsonPath('orders.data.0.status', 'pending')
            ->assertJsonPath('summary.pending', 1)
            ->assertJsonPath('summary.preparing', 1)
            ->assertJsonPath('available_statuses', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'delivered',
                'completed',
                'cancelled',
            ])
            ->assertJsonStructure(['refreshed_at']);
    }

    public function test_non_manager_cannot_access_order_supervision(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->getJson(route('gerant.orders.index'))
            ->assertForbidden();
    }

    public function test_manager_can_change_order_status(): void
    {
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $order = $this->createOrder($customer, 'pending');

        $this->actingAs($manager)
            ->patchJson(route('gerant.orders.status.update', $order), ['status' => 'preparing'])
            ->assertOk()
            ->assertJsonPath('order.status', 'preparing');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'preparing',
        ]);
    }

    public function test_non_manager_cannot_change_order_status(): void
    {
        $employee = $this->userWithRole('employee');
        $customer = $this->userWithRole('student');
        $order = $this->createOrder($customer, 'pending');

        $this->actingAs($employee)
            ->patchJson(route('gerant.orders.status.update', $order), ['status' => 'ready'])
            ->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_manager_cannot_set_an_unsupported_order_status(): void
    {
        $manager = $this->userWithRole('manager');
        $customer = $this->userWithRole('student');
        $order = $this->createOrder($customer, 'pending');

        $this->actingAs($manager)
            ->patchJson(route('gerant.orders.status.update', $order), ['status' => 'unknown'])
            ->assertUnprocessable();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
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

    private function createOrder(User $customer, string $status): Order
    {
        return Order::create([
            'order_number' => strtoupper($status).'-'.uniqid(),
            'user_id' => $customer->id,
            'service_mode' => 'on_site',
            'status' => $status,
        ]);
    }
}