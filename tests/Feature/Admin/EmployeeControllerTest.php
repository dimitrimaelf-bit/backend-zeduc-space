<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $employeeRole;
    protected $gerantRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialisation des rôles (1: étudiant, 2: employé, 3: gérant, 4: admin)
        Role::create(['id' => 1, 'code' => 'etudiant', 'label' => 'Étudiant']);
        $this->employeeRole = Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        $this->gerantRole   = Role::create(['id' => 3, 'code' => 'gerant', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);

        // Création de l'Administrateur avec mot de passe renseigné
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
    public function un_non_admin_ne_peut_pas_acceder_a_la_gestion_des_employes()
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

        $response = $this->actingAs($student)->get(route('admin.employees.index'));
        $response->assertStatus(403);
    }

    #[Test]
    public function un_admin_peut_voir_la_liste_des_employes_et_gerants()
    {
        User::create([
            'first_name'    => 'EmployeTest',
            'last_name'     => 'User',
            'email'         => 'emp@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 2,
            'is_active'     => true,
        ]);

        User::create([
            'first_name'    => 'GerantTest',
            'last_name'     => 'User',
            'email'         => 'gerant@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 3,
            'is_active'     => true,
        ]);

        $resEmp = $this->actingAs($this->admin)->get(route('admin.employees.index', ['role' => 'employee']));
        $resEmp->assertStatus(200)->assertSee('EmployeTest');

        $resGer = $this->actingAs($this->admin)->get(route('admin.employees.index', ['role' => 'gerant']));
        $resGer->assertStatus(200)->assertSee('GerantTest');
    }

    #[Test]
    public function un_admin_peut_creer_un_employe_avec_role_id_2()
    {
        $payload = [
            'first_name' => 'Aïcha',
            'last_name'  => 'Ndong',
            'email'      => 'aicha.employe@zeducspace.cm',
            'phone'      => '699001122',
            'password'   => 'Password123!',
            'role_code'  => 'employee',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), $payload);

        $response->assertRedirect(route('admin.employees.index', ['role' => 'employee']));
        $this->assertDatabaseHas('users', [
            'email'   => 'aicha.employe@zeducspace.cm',
            'role_id' => 2,
        ]);
    }

    #[Test]
    public function un_admin_peut_creer_un_gerant_avec_role_id_3()
    {
        $payload = [
            'first_name' => 'Marc',
            'last_name'  => 'Mbida',
            'email'      => 'marc.gerant@zeducspace.cm',
            'phone'      => '677001122',
            'password'   => 'Password123!',
            'role_code'  => 'gerant',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), $payload);

        $response->assertRedirect(route('admin.employees.index', ['role' => 'gerant']));
        $this->assertDatabaseHas('users', [
            'email'   => 'marc.gerant@zeducspace.cm',
            'role_id' => 3,
        ]);
    }

    #[Test]
    public function un_admin_peut_modifier_un_compte()
    {
        $user = User::create([
            'first_name'    => 'Ancien',
            'last_name'     => 'User',
            'email'         => 'old@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 2,
            'is_active'     => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.employees.update', $user->id), [
            'first_name' => 'Nouveau',
            'last_name'  => $user->last_name,
            'email'      => $user->email,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'         => $user->id,
            'first_name' => 'Nouveau',
        ]);
    }

    #[Test]
    public function un_admin_peut_supprimer_un_compte()
    {
        $user = User::create([
            'first_name'    => 'ToDelete',
            'last_name'     => 'User',
            'email'         => 'delete@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 2,
            'is_active'     => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.employees.destroy', $user->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}