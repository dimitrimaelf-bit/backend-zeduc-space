<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['id' => 1, 'code' => 'etudiant', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'gerant', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);

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
    public function un_non_admin_ne_peut_pas_acceder_au_dashboard()
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

        $response = $this->actingAs($student)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    #[Test]
    public function un_admin_peut_acceder_au_dashboard()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}