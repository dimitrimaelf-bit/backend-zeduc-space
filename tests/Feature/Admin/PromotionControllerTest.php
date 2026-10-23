<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;


class PromotionControllerTest extends TestCase
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
    public function un_admin_peut_consulter_les_promotions()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.promotions.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function un_admin_peut_creer_une_promotion_avec_une_affiche()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.promotions.store'), [
            'title' => 'Offre déjeuner',
            'description' => 'Menu à prix réduit.',
            'image_url' => 'https://example.test/promo.jpg',
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'ends_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.promotions.index'));
        $this->assertDatabaseHas('promotions', [
            'title' => 'Offre déjeuner',
            'image_url' => 'https://example.test/promo.jpg',
            'is_active' => 1,
        ]);
    }

    #[Test]
    public function un_admin_peut_televerser_une_affiche_de_promotion()
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('admin.promotions.store'), [
            'title' => 'Affiche importée',
            'description' => 'Promotion avec fichier local.',
            'image_file' => UploadedFile::fake()->createWithContent('promo.jpg', base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAb/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCwAAf/2Q==')),
            'starts_at' => now()->subDay()->format('Y-m-d H:i:s'),
            'ends_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'is_active' => '1',
        ])->assertRedirect(route('admin.promotions.index'));

        $promotion = \App\Models\Promotion::where('title', 'Affiche importée')->firstOrFail();
        $this->assertStringContainsString('/storage/promotions/', $promotion->image_url);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', parse_url($promotion->image_url, PHP_URL_PATH)));
    }
}