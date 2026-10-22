<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClaimControllerTest extends TestCase
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
    public function un_admin_peut_consulter_les_reclamations()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.claims.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function un_admin_peut_repondre_a_une_reclamation()
    {
        $student = User::create([
            'first_name' => 'Etudiant',
            'last_name' => 'Test',
            'email' => 'student-claim@example.test',
            'password' => bcrypt('Password123!'),
            'role_id' => 1,
            'is_active' => true,
        ]);
        $order = \App\Models\Order::create([
            'order_number' => 'CLAIM-001',
            'user_id' => $student->id,
            'service_mode' => 'on_site',
            'status' => 'completed',
        ]);
        $claim = \App\Models\Claim::create([
            'order_id' => $order->id,
            'user_id' => $student->id,
            'subject' => 'Commande froide',
            'description' => 'Le plat était froid.',
            'status' => 'open',
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.claims.update', $claim), [
                'status' => 'resolved',
                'response' => 'Nous sommes désolés.',
            ])
            ->assertRedirect(route('admin.claims.index'));

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => 'resolved',
            'response' => 'Nous sommes désolés.',
            'handled_by' => $this->admin->id,
        ]);
    }
}