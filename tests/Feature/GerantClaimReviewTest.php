<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantClaimReviewTest extends TestCase
{
    // Réinitialise la base de données après chaque test pour isoler les scénarios.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndSettingsSeeder::class);
    }

    // Vérifie qu'un gérant consulte les réclamations et leurs compteurs par état.
    public function test_manager_can_consult_claims_and_status_totals(): void
    {
        // Prépare un gérant et une réclamation ayant une réponse à examiner.
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('in_progress', 'Réponse proposée par employé');

        // Vérifie que la page Blade expose les réclamations et leurs compteurs.
        $this->actingAs($manager)
            ->get(route('gerant.claims.index'))
            ->assertOk()
            ->assertViewIs('Gerant.Claims')
            ->assertViewHas('claims', fn ($claims) => $claims->first()?->id === $claim->id)
            ->assertViewHas('summary', fn ($summary) => $summary['in_progress'] === 1)
            ->assertSee('Réponse proposée par employé');
    }

    public function test_manager_can_filter_claims_by_processing_stage(): void
    {
        $manager = $this->userWithRole('manager');
        $openClaim = $this->createClaim('open', null);
        $inProgressClaim = $this->createClaim('in_progress', 'Réponse en attente');
        $resolvedClaim = $this->createClaim('resolved', 'Réponse approuvée');
        $rejectedClaim = $this->createClaim('rejected', 'Réponse refusée');

        $this->actingAs($manager)
            ->get(route('gerant.claims.index', ['filter' => 'to_process']))
            ->assertOk()
            ->assertViewHas('claims', fn ($claims) => $claims->pluck('id')->sort()->values()->all()
                === collect([$openClaim->id, $inProgressClaim->id])->sort()->values()->all())
            ->assertSee('À traiter')
            ->assertDontSee('Rejeter / Ajuster');

        $this->get(route('gerant.claims.index', ['filter' => 'processed']))
            ->assertOk()
            ->assertViewHas('claims', fn ($claims) => $claims->pluck('id')->sort()->values()->all()
                === collect([$resolvedClaim->id, $rejectedClaim->id])->sort()->values()->all())
            ->assertSee('Traitées')
            ->assertDontSee('Valider la solution');
    }

    // Vérifie que l'approbation d'une réponse clôt la réclamation comme résolue.
    public function test_manager_can_approve_an_employee_response(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('in_progress', 'Réponse à valider');

        $this->actingAs($manager)
            ->patch(route('gerant.claims.review', $claim), ['decision' => 'approved'])
            ->assertRedirect(route('gerant.claims.index'))
            ->assertSessionHas('status', 'La réclamation a été examinée.');

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => 'resolved',
        ]);
    }

    // Vérifie que le rejet d'une réponse place la réclamation dans l'état rejeté.
    public function test_manager_can_reject_an_employee_response(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('in_progress', 'Réponse à rejeter');

        $this->actingAs($manager)
            ->patch(route('gerant.claims.review', $claim), ['decision' => 'rejected'])
            ->assertRedirect(route('gerant.claims.index'));

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => 'rejected',
        ]);
    }

    // Une réclamation ouverte sans réponse ne doit pas pouvoir être examinée.
    public function test_claim_without_proposed_response_cannot_be_reviewed(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('open', null);

        $this->actingAs($manager)
            ->patch(route('gerant.claims.review', $claim), ['decision' => 'approved'])
            ->assertSessionHasErrors('decision');
    }

    // Vérifie que les utilisateurs non gérants ne peuvent ni consulter ni examiner les réclamations.
    public function test_non_manager_cannot_consult_or_review_claims(): void
    {
        $employee = $this->userWithRole('employee');
        $claim = $this->createClaim('in_progress', 'Réponse proposée');

        $this->actingAs($employee)
            ->get(route('gerant.claims.index'))
            ->assertForbidden();

        $this->actingAs($employee)
            ->patch(route('gerant.claims.review', $claim), ['decision' => 'approved'])
            ->assertForbidden();
    }

    // Crée les données liées nécessaires à une réclamation de test.
    private function createClaim(string $status, ?string $response): Claim
    {
        // La réclamation appartient à un étudiant et se rattache à une commande.
        $student = $this->userWithRole('student');
        $order = Order::create([
            'order_number' => 'ORDER-CLAIM-'.uniqid(),
            'user_id' => $student->id,
            'service_mode' => 'on_site',
            'status' => 'completed',
        ]);

        return Claim::create([
            'order_id' => $order->id,
            'user_id' => $student->id,
            'subject' => 'Réclamation de test',
            'description' => 'Description de test',
            'status' => $status,
            'response' => $response,
            // Une réclamation en cours est associée à l'employé qui a répondu.
            'handled_by' => $status === 'in_progress' ? $this->userWithRole('employee')->id : null,
        ]);
    }

    // Crée un utilisateur avec le rôle requis pour le scénario.
    private function userWithRole(string $roleCode): User
    {
        // Garantit la présence du rôle avant de créer l'utilisateur.
        $role = Role::firstOrCreate(
            ['code' => $roleCode],
            ['label' => ucfirst($roleCode)]
        );

        // Utilise une adresse unique pour éviter les collisions entre créations.
        return User::create([
            'role_id' => $role->id,
            'first_name' => ucfirst($roleCode),
            'last_name' => 'Test',
            'email' => $roleCode.'-'.uniqid().'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}