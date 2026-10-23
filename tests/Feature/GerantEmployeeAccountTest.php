<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantEmployeeAccountTest extends TestCase
{
    // Recrée une base propre avant chaque scénario.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndSettingsSeeder::class);
    }

    // Vérifie qu'un gérant peut créer un compte employé actif sans révéler son mot de passe.
    public function test_manager_can_create_an_active_employee_account(): void
    {
        // Prépare l'utilisateur autorisé à créer le compte.
        $manager = $this->userWithRole('manager');

        // Crée le compte, puis vérifie la réponse et le mot de passe enregistré.
        $this->actingAs($manager)
            ->post(route('gerant.employees.store'), [
                'first_name' => 'Alex',
                'last_name' => 'Employe',
                'email' => 'alex@example.test',
                'phone' => '0123456789',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('gerant.employees.index'))
            ->assertSessionHas('status', 'Le compte employé a été créé.');

        $employee = User::where('email', 'alex@example.test')->firstOrFail();
        $this->assertSame('employee', $employee->role->code);
        $this->assertTrue(Hash::check('Password123!', $employee->password));
    }

    // Vérifie qu'un employé ne peut pas créer un autre compte employé.
    public function test_non_manager_cannot_create_an_employee_account(): void
    {
        $employee = $this->userWithRole('employee');

        $this->actingAs($employee)
            ->post(route('gerant.employees.store'), [])
            ->assertForbidden();
    }

    // Vérifie que l'authentification refuse un compte employé désactivé.
    public function test_inactive_employee_cannot_log_in(): void
    {
        $employee = $this->userWithRole('employee');
        $employee->update(['password' => Hash::make('Password123!'), 'is_active' => false]);

        $this->post('/login', [
            'email' => $employee->email,
            'password' => 'Password123!',
        ])->assertSessionHasErrors('email');
    }

    // Crée un utilisateur de test lié au rôle demandé.
    private function userWithRole(string $roleCode): User
    {
        // Crée le rôle uniquement s'il n'existe pas déjà dans la base du test.
        $role = Role::firstOrCreate(
            ['code' => $roleCode],
            ['label' => ucfirst($roleCode)]
        );

        // Renvoie l'utilisateur persisté avec un mot de passe haché.
        return User::create([
            'role_id' => $role->id,
            'first_name' => ucfirst($roleCode),
            'last_name' => 'Test',
            'email' => $roleCode.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}