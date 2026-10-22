<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\ReferralCode;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    // Affiche le formulaire d'inscription
    public function create()
    {
        return view('auth.register');
    }

    // Reçoit les données validées, crée l'utilisateur
    // et redirige vers l'accueil étudiant
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $studentRole = Role::whereIn('code', ['student', 'etudiant'])->firstOrFail();
        $referralCode = null;

        if (!empty($validated['referral_code'])) {
            $referralCode = ReferralCode::where('code', $validated['referral_code'])
                ->where('is_active', true)
                ->first();

            if (!$referralCode) {
                throw ValidationException::withMessages([
                    'referral_code' => 'Ce code de parrainage est invalide.',
                ]);
            }
        }

        // Transaction : soit tout réussit, soit rien n'est enregistré
        $user = DB::transaction(function () use ($validated, $studentRole, $referralCode) {
            $user = User::create([
                ...$validated,
                'role_id'   => $studentRole->id,
                'is_active' => true,
            ]);

            // Enregistrer le parrainage si un code a été utilisé
            if ($referralCode) {
                Referral::create([
                    'referrer_user_id' => $referralCode->user_id,
                    'referred_user_id' => $user->id,
                    'code_id'          => $referralCode->id,
                    'status'           => 'pending',
                ]);
            }

            // Code de parrainage propre au nouvel utilisateur
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

    // Génère un code de parrainage unique (ex : 8B3X9K1Z)
    private function generateUniqueReferralCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (ReferralCode::where('code', $code)->exists());

        return $code;
    }
}