<?php

namespace Tests\Feature;

use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndSettingsSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndSettingsSeeder::class);
    }

    public function test_registration_saves_form_values_to_their_matching_columns(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Amina',
            'last_name' => 'Nana',
            'email' => 'amina@example.com',
            'phone' => '690001122',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'delivery_address' => 'Campus de Yansoki',
        ]);

        $user = User::where('email', 'amina@example.com')->firstOrFail();

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame(Role::where('code', 'student')->value('id'), $user->role_id);
        $this->assertSame('Campus de Yansoki', $user->delivery_address);
        $this->assertSame('690001122', $user->phone);
        $this->assertTrue(Hash::check('Password123!', $user->password));
        $this->assertDatabaseMissing('users', ['email' => 'amina@example.com', 'password' => 'Password123!']);
    }

    public function test_registration_saves_referral_relationship_and_allows_optional_phone(): void
    {
        $referrer = User::create([
            'role_id' => Role::where('code', 'student')->value('id'),
            'first_name' => 'Jean',
            'last_name' => 'Referrer',
            'email' => 'jean@example.com',
            'password' => 'Password123!',
        ]);
        $referralCode = ReferralCode::create([
            'user_id' => $referrer->id,
            'code' => 'JEAN2026',
        ]);

        $response = $this->post('/register', [
            'first_name' => 'Amina',
            'last_name' => 'Nana',
            'email' => 'amina@example.com',
            'phone' => '',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'delivery_address' => '',
            'referral_code' => $referralCode->code,
        ]);

        $referred = User::where('email', 'amina@example.com')->firstOrFail();

        $response->assertRedirect(route('home'));
        $this->assertNull($referred->phone);
        $this->assertDatabaseHas('referrals', [
            'referrer_user_id' => $referrer->id,
            'referred_user_id' => $referred->id,
            'code_id' => $referralCode->id,
            'status' => 'pending',
        ]);
        $this->assertSame(1, Referral::where('referred_user_id', $referred->id)->count());
    }

    public function test_registration_rejects_unknown_referral_codes_without_creating_a_user(): void
    {
        $response = $this->from('/register')->post('/register', [
            'first_name' => 'Amina',
            'last_name' => 'Nana',
            'email' => 'amina@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'referral_code' => 'UNKNOWN',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('referral_code');
        $this->assertDatabaseMissing('users', ['email' => 'amina@example.com']);
    }

    public function test_password_reset_form_saves_token_and_updates_the_user_password(): void
    {
        Notification::fake();

        $user = User::create([
            'role_id' => Role::where('code', 'student')->value('id'),
            'first_name' => 'Amina',
            'last_name' => 'Nana',
            'email' => 'amina@example.com',
            'password' => 'OldPassword123!',
        ]);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        $this->get(route('password.reset', ['token' => $token]).'?email='.$user->email)
            ->assertOk()
            ->assertSee('name="token" value="'.$token.'"', false);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }
}
