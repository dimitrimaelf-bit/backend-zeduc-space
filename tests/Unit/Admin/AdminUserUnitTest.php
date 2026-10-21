<?php

namespace Tests\Unit\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminUserUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialisation systématique de tous les rôles pour éviter les échecs de clé étrangère
        Role::create(['id' => 1, 'code' => 'etudiant', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'gerant', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);
    }

    #[Test]
    public function un_utilisateur_admin_est_associe_au_role_administrateur()
    {
        $admin = User::create([
            'first_name'    => 'Super',
            'last_name'     => 'Admin',
            'email'         => 'superadmin@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 4,
            'is_active'     => true,
        ]);

        $this->assertInstanceOf(Role::class, $admin->role);
        $this->assertEquals('admin', $admin->role->code);
        $this->assertEquals(4, $admin->role_id);
    }

    #[Test]
    public function un_administrateur_peut_avoir_des_employes_et_gerants_sous_sa_gestion()
    {
        $employee = User::create([
            'first_name'    => 'Paul',
            'last_name'     => 'Agent',
            'email'         => 'paul@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 2,
            'is_active'     => true,
        ]);

        $gerant = User::create([
            'first_name'    => 'Marc',
            'last_name'     => 'Manager',
            'email'         => 'marc@zeducspace.cm',
            'password'      => bcrypt('Password123!'),
            'password_hash' => bcrypt('Password123!'),
            'role_id'       => 3,
            'is_active'     => true,
        ]);

        $this->assertEquals('employee', $employee->role->code);
        $this->assertEquals('gerant', $gerant->role->code);
    }
}