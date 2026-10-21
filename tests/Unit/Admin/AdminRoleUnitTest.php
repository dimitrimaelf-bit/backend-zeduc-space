<?php

namespace Tests\Unit\Admin;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminRoleUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function les_roles_de_l_espace_administration_peuvent_etre_crees_et_verifies()
    {
        $roleAdmin = Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);
        $roleEmployee = Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        $roleGerant = Role::create(['id' => 3, 'code' => 'gerant', 'label' => 'Gérant']);

        $this->assertDatabaseHas('roles', ['code' => 'admin']);
        $this->assertDatabaseHas('roles', ['code' => 'employee']);
        $this->assertDatabaseHas('roles', ['code' => 'gerant']);
    }
}