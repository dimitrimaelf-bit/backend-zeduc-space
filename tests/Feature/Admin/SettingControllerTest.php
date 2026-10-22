<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SettingControllerTest extends TestCase
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
    public function un_admin_peut_consulter_les_parametres()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function un_admin_peut_modifier_un_parametre_de_fidelite()
    {
        $this->actingAs($this->admin)
            ->put(route('admin.settings.update', 'loyalty_fcfa_per_point'), [
                'setting_value' => '1500',
            ])
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('app_settings', [
            'setting_key' => 'loyalty_fcfa_per_point',
            'setting_value' => '1500',
            'updated_by' => $this->admin->id,
        ]);
    }

    #[Test]
    public function un_admin_peut_configurer_les_liens_legaux_et_la_version_cookie()
    {
        foreach ([
            'privacy_policy_url' => 'https://example.test/privacy',
            'terms_of_service_url' => 'https://example.test/terms',
            'cookie_policy_version' => '2.1',
        ] as $key => $value) {
            $this->actingAs($this->admin)
                ->put(route('admin.settings.update', $key), ['setting_value' => $value])
                ->assertRedirect(route('admin.settings.index'));

            $this->assertDatabaseHas('app_settings', [
                'setting_key' => $key,
                'setting_value' => $value,
                'updated_by' => $this->admin->id,
            ]);
        }
    }
}