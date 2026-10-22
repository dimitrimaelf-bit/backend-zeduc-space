<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerantClaimReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_consult_claims_and_status_totals(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('in_progress', 'Réponse proposée par employé');

        $this->actingAs($manager)
            ->getJson(route('gerant.claims.index'))
            ->assertOk()
            ->assertJsonPath('claims.data.0.id', $claim->id)
            ->assertJsonPath('claims.data.0.response', 'Réponse proposée par employé')
            ->assertJsonPath('summary.in_progress', 1)
            ->assertJsonStructure(['refreshed_at']);
    }

    public function test_manager_can_approve_an_employee_response(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('in_progress', 'Réponse à valider');

        $this->actingAs($manager)
            ->patchJson(route('gerant.claims.review', $claim), ['decision' => 'approved'])
            ->assertOk()
            ->assertJsonPath('claim.status', 'resolved')
            ->assertJsonPath('claim.response', 'Réponse à valider');

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => 'resolved',
        ]);
    }

    public function test_manager_can_reject_an_employee_response(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('in_progress', 'Réponse à rejeter');

        $this->actingAs($manager)
            ->patchJson(route('gerant.claims.review', $claim), ['decision' => 'rejected'])
            ->assertOk()
            ->assertJsonPath('claim.status', 'rejected');
    }

    public function test_claim_without_proposed_response_cannot_be_reviewed(): void
    {
        $manager = $this->userWithRole('manager');
        $claim = $this->createClaim('open', null);

        $this->actingAs($manager)
            ->patchJson(route('gerant.claims.review', $claim), ['decision' => 'approved'])
            ->assertUnprocessable();
    }

    public function test_non_manager_cannot_consult_or_review_claims(): void
    {
        $employee = $this->userWithRole('employee');
        $claim = $this->createClaim('in_progress', 'Réponse proposée');

        $this->actingAs($employee)
            ->getJson(route('gerant.claims.index'))
            ->assertForbidden();

        $this->actingAs($employee)
            ->patchJson(route('gerant.claims.review', $claim), ['decision' => 'approved'])
            ->assertForbidden();
    }

    private function createClaim(string $status, ?string $response): Claim
    {
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
            'handled_by' => $status === 'in_progress' ? $this->userWithRole('employee')->id : null,
        ]);
    }

    private function userWithRole(string $roleCode): User
    {
        $role = Role::firstOrCreate(
            ['code' => $roleCode],
            ['label' => ucfirst($roleCode)]
        );

        return User::create([
            'role_id' => $role->id,
            'first_name' => ucfirst($roleCode),
            'last_name' => 'Test',
            'email' => $roleCode.'-'.uniqid().'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}