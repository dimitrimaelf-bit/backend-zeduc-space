<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Affiche le formulaire d'inscription
    public function create()
    {
        return view('auth.register');
    }

    // Reçoit les données validées, crée l'utilisateur et son parrainage éventuel.
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        // On utilise une transaction car le traitement de l'inscription et du parrainage doit être atomique : soit tout réussit, soit rien n'est enregistré.
        $user = DB::transaction(function () use ($data) {
            $studentRole = Role::where('code', 'student')->firstOrFail();

            $referralCode = isset($data['referral_code'])
                ? ReferralCode::where('code', $data['referral_code'])
                    ->where('is_active', true)
                    ->firstOrFail()
                : null;

            // 1. Création de l'utilisateur
            $user = User::create([
                'role_id'          => $studentRole->id,
                'first_name'       => $data['first_name'],
                'last_name'        => $data['last_name'],
                'email'            => $data['email'],
                'phone'            => $data['phone'] ?? null,
                'password'         => $data['password'],
                'delivery_address' => $data['delivery_address'] ?? null,
            ]);

            // 2. Si l'utilisateur s'est inscrit avec un code parrain, enregistrer le parrainage
            if ($referralCode) {
                Referral::create([
                    'referrer_user_id' => $referralCode->user_id,
                    'referred_user_id' => $user->id,
                    'code_id'          => $referralCode->id,
                ]);
            }

            // 3. Génération du propre code de parrainage du nouvel utilisateur
            ReferralCode::create([
                'user_id'   => $user->id,
                'code'      => $this->generateUniqueReferralCode(),
                'is_active' => true,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('etudiant.accueil');
    }

    /**
     * Génère un code de parrainage unique composé de chiffres et lettres majuscules (ex: 8B3X9K1Z)
     */
    private function generateUniqueReferralCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (ReferralCode::where('code', $code)->exists());

        return $code;
    }
}