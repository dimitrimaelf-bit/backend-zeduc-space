<?php

namespace Tests\Feature\Admin;

use App\Models\LoyaltyAccount;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoyaltyAdjustmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_credit_points_to_a_student_with_an_audited_expiry(): void
    {
        Role::create(['id' => 1, 'code' => 'student', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'manager', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);
        $admin = User::create([
            'role_id' => 4,
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin-loyalty@example.test',
            'password' => 'Password123!',
        ]);
        $student = User::create([
            'role_id' => 1,
            'first_name' => 'Etudiant',
            'last_name' => 'Test',
            'email' => 'student-loyalty@example.test',
            'password' => 'Password123!',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.loyalty.adjust'), [
                'email' => $student->email,
                'points' => 20,
                'description' => 'Correction validée par la direction',
            ])
            ->assertRedirect(route('admin.stats'));

        $this->assertSame(20, LoyaltyAccount::where('user_id', $student->id)->value('balance_points'));
        $this->assertDatabaseHas('loyalty_transactions', [
            'user_id' => $student->id,
            'type' => 'adjust',
            'points' => 20,
            'description' => 'Correction validée par la direction',
        ]);
        $this->assertNotNull(
            \App\Models\LoyaltyTransaction::where('user_id', $student->id)->value('expires_at')
        );
    }
}
