<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_category_using_the_persisted_fields(): void
    {
        Role::create(['id' => 1, 'code' => 'student', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'manager', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);
        $admin = User::create([
            'role_id' => 4,
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin-category@example.test',
            'password' => 'Password123!',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Boissons',
                'sort_order' => 2,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('menu_categories', [
            'name' => 'Boissons',
            'sort_order' => 2,
            'is_active' => 1,
        ]);
    }
}
