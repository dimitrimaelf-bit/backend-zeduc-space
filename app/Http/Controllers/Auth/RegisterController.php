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
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    // affiche le formulaire d'inscription
    public function create() 
    {
        return view('auth.register');
    }

    // reçoit les données validées, crée l'utilisateur 
    // et redirige vers le tableau de bord
    public function store(StoreUserRequest $request) 
    {
        $validated = $request->validated();
        $studentRole = Role::whereIn('code', ['student', 'etudiant'])->firstOrFail();
        $referralCode = null;

        if (!empty($validated['referral_code'])) {
            $referralCode = ReferralCode::where('code', $validated['referral_code'])->first();
            if (!$referralCode) {
                throw ValidationException::withMessages(['referral_code' => 'Ce code de parrainage est invalide.']);
            }
        }

        $user = DB::transaction(function () use ($validated, $studentRole, $referralCode) {
            $user = User::create([
                ...$validated,
                'role_id' => $studentRole->id,
                'is_active' => true,
            ]);

            if ($referralCode) {
                Referral::create([
                    'referrer_user_id' => $referralCode->user_id,
                    'referred_user_id' => $user->id,
                    'code_id' => $referralCode->id,
                    'status' => 'pending',
                ]);
            }

            return $user;
        });

        Auth::login($user);
        return redirect()->route('home');
    }
}
