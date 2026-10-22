<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_uses_configured_loyalty_rate_without_awarding_points_before_payment(): void
    {
        $studentRole = Role::create(['code' => 'student', 'label' => 'Étudiant']);
        $student = User::create([
            'role_id' => $studentRole->id,
            'first_name' => 'Amina',
            'last_name' => 'Test',
            'email' => 'student-order@example.test',
            'password' => 'Password123!',
        ]);
        $category = MenuCategory::create(['name' => 'Plats', 'sort_order' => 1, 'is_active' => true]);
        $item = MenuItem::create([
            'category_id' => $category->id,
            'name' => 'Plat test',
            'description' => '',
            'price_fcfa' => 3000,
            'is_available' => true,
        ]);
        AppSetting::create([
            'setting_key' => 'loyalty_fcfa_per_point',
            'setting_value' => '1500',
        ]);

        $this->actingAs($student)
            ->withSession(['cart' => [[
                'id' => $item->id,
                'name' => $item->name,
                'price_fcfa' => $item->price_fcfa,
                'quantity' => 1,
            ]]])
            ->post(route('etudiant.order.store'), ['service_mode' => 'on_site'])
            ->assertRedirect(route('etudiant.menu.index'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $student->id,
            'total_fcfa' => 3000,
            'loyalty_points_earned' => 2,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => \App\Models\Order::where('user_id', $student->id)->value('id'),
            'method' => 'cash',
            'status' => 'pending',
            'amount_fcfa' => 3000,
        ]);
        $this->assertDatabaseMissing('loyalty_accounts', ['user_id' => $student->id]);
        $this->assertSame(0, LoyaltyTransaction::where('user_id', $student->id)->count());
    }
}
