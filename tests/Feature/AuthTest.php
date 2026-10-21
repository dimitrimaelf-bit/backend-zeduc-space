<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Charge les rôles (id 2 = student, id 4 = admin)
        $this->seed(RolesAndSettingsSeeder::class);
    }

    /** @test */
    public function un_utilisateur_actif_peut_se_connecter()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'role_id' => 2, // Role étudiant
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function un_utilisateur_inactif_ne_peut_pas_se_connecter()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'role_id' => 2,
            'email' => 'inactif@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactif@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function un_etudiant_peut_s_inscrire()
    {
        $response = $this->post('/register', [
            'role_id' => 'etudiant',
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean.dupont@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jean.dupont@example.com',
            'role_id' => 2,
        ]);

        $this->assertAuthenticated();
    }

    /** @test */
    public function un_etudiant_ne_peut_pas_acceder_a_l_espace_admin()
    {
        /** @var User $student */
        $student = User::factory()->create([
            'role_id' => 2, // Role étudiant
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->get('/admin/dashboard');

        $response->assertStatus(403);
    }
}