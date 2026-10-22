<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantEmployeeAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_an_active_employee_account(): void
    {
        $manager = $this->userWithRole('manager');

        $this->actingAs($manager)
            ->postJson(route('gerant.employees.store'), [
                'first_name' => 'Alex',
                'last_name' => 'Employe',
                'email' => 'alex@example.test',
                'phone' => '0123456789',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertCreated()
            ->assertJsonPath('employee.email', 'alex@example.test')
            ->assertJsonPath('employee.is_active', true)
            ->assertJsonMissingPath('employee.password');

        $employee = User::where('email', 'alex@example.test')->firstOrFail();
        $this->assertSame('employee', $employee->role->code);
        $this->assertTrue(Hash::check('Password123!', $employee->password));
    }

    public function test_non_manager_cannot_create_an_employee_account(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->postJson(route('gerant.employees.store'), [])
            ->assertForbidden();
    }

    public function test_manager_can_enable_or_disable_employee_access(): void
    {
        $manager = $this->userWithRole('manager');
        $employee = $this->userWithRole('employee');

        $this->actingAs($manager)
            ->patchJson(route('gerant.employees.access.update', $employee), [
                'is_active' => false,
            ])
            ->assertOk()
            ->assertJsonPath('employee.is_active', false);

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'is_active' => false,
        ]);

        $this->actingAs($manager)
            ->patchJson(route('gerant.employees.access.update', $employee), [
                'is_active' => true,
            ])
            ->assertOk()
            ->assertJsonPath('employee.is_active', true);
    }

    public function test_non_manager_cannot_change_employee_access(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->patchJson(route('gerant.employees.access.update', $employee), [
                'is_active' => false,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'is_active' => true,
        ]);
    }

    public function test_inactive_employee_cannot_log_in(): void
    {
        $employee = $this->userWithRole('employee');
        $employee->update(['password' => Hash::make('Password123!'), 'is_active' => false]);

        $this->post('/login', [
            'email' => $employee->email,
            'password' => 'Password123!',
        ])->assertSessionHasErrors('email');
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