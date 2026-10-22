<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_event_poster(): void
    {
        Role::create(['id' => 1, 'code' => 'student', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'manager', 'label' => 'Gérant']);
        Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);
        $admin = User::create([
            'role_id' => 4,
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin-event@example.test',
            'password' => 'Password123!',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.events.store'), [
                'title' => 'Soirée campus',
                'type' => 'Animation',
                'description' => 'Événement étudiant.',
                'image_url' => 'https://example.test/event.jpg',
                'starts_at' => now()->format('Y-m-d H:i:s'),
                'ends_at' => now()->addDay()->format('Y-m-d H:i:s'),
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.promotions.index'));

        $this->assertDatabaseHas('events', [
            'title' => 'Soirée campus',
            'type' => 'Animation',
            'image_url' => 'https://example.test/event.jpg',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_upload_an_event_poster_file(): void
    {
        Storage::fake('public');
        Role::create(['id' => 1, 'code' => 'student', 'label' => 'Étudiant']);
        Role::create(['id' => 2, 'code' => 'employee', 'label' => 'Employé']);
        Role::create(['id' => 3, 'code' => 'manager', 'label' => 'Gérant']);
        $adminRole = Role::create(['id' => 4, 'code' => 'admin', 'label' => 'Administrateur']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'first_name' => 'Admin',
            'last_name' => 'Upload',
            'email' => 'admin-event-upload@example.test',
            'password' => 'Password123!',
        ]);

        $this->actingAs($admin)->post(route('admin.events.store'), [
            'title' => 'Événement avec fichier',
            'image_file' => UploadedFile::fake()->createWithContent('event.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/lWQAAAAASUVORK5CYII=')),
            'is_active' => '1',
        ])->assertRedirect(route('admin.promotions.index'));

        $event = \App\Models\Event::where('title', 'Événement avec fichier')->firstOrFail();
        $this->assertStringContainsString('/storage/events/', $event->image_url);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', parse_url($event->image_url, PHP_URL_PATH)));
    }
}
