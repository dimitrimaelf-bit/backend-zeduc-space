<?php

use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

describe('login', function () {

    test('un invité voit la page de login', function () {
        $this->get(route('login'))->assertOk();
    });

    test('un utilisateur actif peut se connecter avec de bons identifiants', function () {
        $user = User::factory()->create([
            'email' => 'etudiant@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'etudiant@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('etudiant.accueil'));
        $this->assertAuthenticatedAs($user);
    });

    test('un mauvais mot de passe renvoie une erreur', function () {
        User::factory()->create([
            'email' => 'etudiant@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'etudiant@example.com',
            'password' => 'mauvais',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('un email inexistant renvoie une erreur', function () {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'inconnu@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('un compte inactif ne peut pas se connecter', function () {
        $user = User::factory()->inactive()->create([
            'email' => 'inactif@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'inactif@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('la validation échoue si email ou password manque', function () {
        $response = $this->from(route('login'))->post(route('login'), []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    });

    test('un email invalide est rejeté', function () {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'pas-un-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('remember me pose bien le cookie de rappel', function () {
        $user = User::factory()->create([
            'email' => 'remember@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'remember@example.com',
            'password' => 'password123',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('etudiant.accueil'));
        $this->assertAuthenticatedAs($user);

        // 1. Récupère le cookie dont le nom commence par 'remember_web'
        $rememberCookie = collect($response->headers->getCookies())
            ->first(fn ($c) => str_starts_with($c->getName(), 'remember_web'));

        // 2. Vérifie que le cookie existe bien
        $this->assertNotNull($rememberCookie, 'Le cookie remember_web n\'a pas été trouvé dans la réponse.');
        
        // 3. (Optionnel) Vérifie avec Laravel qu'il s'agit d'un cookie valide et non expiré
        $response->assertCookieNotExpired($rememberCookie->getName());
    });

    test('un utilisateur déjà connecté est redirigé loin de /login (middleware guest)', function () {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect();
    });
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

describe('logout', function () {

    test('un utilisateur authentifié peut se déconnecter', function () {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('un invité ne peut pas appeler /logout', function () {
        $this->post(route('logout'))->assertRedirect(route('login'));
    });
});

/*
|--------------------------------------------------------------------------
| INSCRIPTION
|--------------------------------------------------------------------------
*/

describe('register', function () {

    test('un invité voit la page d\'inscription', function () {
        $this->get(route('register'))->assertOk();
    });

    test('un utilisateur peut s\'inscrire et est automatiquement connecté', function () {
        // 1. Crée le rôle et récupère l'instance
        $role = Role::factory()->student()->create();

        $payload = [
            'role_id'               => $role->id, 
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean.dupont@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone'                 => '690000000',
        ];

        $response = $this->post(route('register'), $payload);

        $response->assertRedirect(route('etudiant.accueil'));
        $this->assertDatabaseHas('users', ['email' => 'jean.dupont@example.com']);
        $this->assertAuthenticated();
    });

    test('l\'inscription échoue si l\'email est déjà pris', function () {
    $role = Role::factory()->student()->create();
    
        // On passe le rôle créé pour éviter que la factory n'essaie d'en réinsérer un nouveau
        User::factory()->create([
            'role_id' => $role->id,
            'email' => 'deja@example.com',
        ]);

        $response = $this->from(route('register'))->post(route('register'), [
            'role_id'               => $role->id,
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'deja@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('l\'inscription échoue si les mots de passe ne correspondent pas', function () {
        Role::factory()->student()->create();

        $response = $this->from(route('register'))->post(route('register'), [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Different123!',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    });

    test('un code de parrainage valide crée bien un Referral', function () {
        // 1. On crée le rôle student
        $role = Role::factory()->student()->create();

        // 2. On réutilise ce rôle pour le parrain afin d'éviter les doublons
        $referrer = User::factory()->create(['role_id' => $role->id]);

        $code = ReferralCode::factory()->create([
            'user_id' => $referrer->id,
            'code' => 'PARRAIN01',
            'is_active' => true,
        ]);

        $response = $this->post(route('register'), [
            'role_id'               => $role->id, // Ne pas oublier role_id requis par la validation
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'filleul@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'referral_code'         => 'PARRAIN01',
        ]);

        $response->assertRedirect(route('etudiant.accueil'));

        $filleul = User::where('email', 'filleul@example.com')->first();
        $this->assertDatabaseHas('referrals', [
            'referrer_user_id' => $referrer->id,
            'referred_user_id' => $filleul->id,
            'code_id'          => $code->id,
        ]);
    });

    test('un code de parrainage inactif fait échouer l\'inscription (firstOrFail)', function () {
        Role::factory()->student()->create();
        ReferralCode::factory()->inactive()->create(['code' => 'INACTIF1']);

        // Le contrôleur fait firstOrFail() → ModelNotFoundException → 404
        $this->withoutExceptionHandling();

        expect(fn () => $this->post(route('register'), [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'filleul@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'referral_code' => 'INACTIF1',
        ]))->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->assertDatabaseMissing('users', ['email' => 'filleul@example.com']);
        $this->assertGuest();
    });
});

/*
|--------------------------------------------------------------------------
| MOT DE PASSE OUBLIÉ / RESET
|--------------------------------------------------------------------------
*/

describe('password reset', function () {

    test('la page "mot de passe oublié" est accessible à un invité', function () {
        $this->get(route('password.request'))->assertOk();
    });

    test('un email valide envoie une notification de reset', function () {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset@example.com']);

        $response = $this->post(route('password.email'), [
            'email' => 'reset@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        Notification::assertSentTo($user, ResetPassword::class);
    });

    test('un email inconnu renvoie une erreur', function () {
        Notification::fake();

        $response = $this->from(route('password.request'))->post(route('password.email'), [
            'email' => 'inconnu@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        Notification::assertNothingSent();
    });

    test('la page reset-password s\'affiche avec le token', function () {
        $this->get(route('password.reset', ['token' => 'faketoken', 'email' => 'a@b.com']))
            ->assertOk()
            ->assertSee('faketoken', false);
    });

    test('un reset valide met à jour le mot de passe et connecte l\'utilisateur', function () {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset@example.com']);

        // Déclenche l'envoi pour récupérer le token réel
        $this->post(route('password.email'), ['email' => 'reset@example.com']);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'reset@example.com',
            'password' => 'NouveauPassword123!',
            'password_confirmation' => 'NouveauPassword123!',
        ]);

        $response->assertRedirect(route('etudiant.accueil'));
        expect(Hash::check('NouveauPassword123!', $user->fresh()->password))->toBeTrue();
    });

    test('un token invalide ne change pas le mot de passe', function () {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('ancien'),
        ]);

        $response = $this->from(route('password.reset', ['token' => 'bidon']))
            ->post(route('password.update'), [
                'token' => 'bidon',
                'email' => 'reset@example.com',
                'password' => 'Nouveau123!',
                'password_confirmation' => 'Nouveau123!',
            ]);

        $response->assertSessionHasErrors('email');
        expect(Hash::check('ancien', $user->fresh()->password))->toBeTrue();
    });
});

/*
|--------------------------------------------------------------------------
| PROTECTION DES ROUTES (authorization)
|--------------------------------------------------------------------------
*/

describe('route protection', function () {

    test('un invité est redirigé vers login pour l\'espace étudiant', function () {
        $this->get(route('etudiant.accueil'))->assertRedirect(route('login'));
    });

    test('un invité est redirigé vers login pour l\'espace admin', function () {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    });

    test('un étudiant peut accéder à son espace', function () {
        $user = User::factory()->student()->create();
        $this->actingAs($user)->get(route('etudiant.accueil'))->assertOk();
    });

    test('un étudiant ne peut pas accéder à l\'espace admin (403)', function () {
        $user = User::factory()->student()->create();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    });

    test('un admin ne peut pas accéder à l\'espace étudiant (403)', function () {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get(route('etudiant.accueil'))->assertForbidden();
    });

    test('un admin peut accéder à son dashboard', function () {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    });

    test('un invité ne peut pas poster le panier', function () {
        $this->post(route('etudiant.cart.add'))->assertRedirect(route('login'));
    });
});