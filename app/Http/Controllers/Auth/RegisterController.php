<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    // affiche le formulaire d'inscription
    public function create()
    {
        return view('auth.register');
    }

    // Reçoit les données validées, crée l'utilisateur et son parrainage éventuel.
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $studentRole = Role::where('code', 'student')->firstOrFail();
            $referralCode = isset($data['referral_code'])
                ? ReferralCode::where('code', $data['referral_code'])
                    ->where('is_active', true)
                    ->firstOrFail()
                : null;

            $user = User::create([
                'role_id' => $studentRole->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'delivery_address' => $data['delivery_address'] ?? null,
            ]);

            if ($referralCode) {
                Referral::create([
                    'referrer_user_id' => $referralCode->user_id,
                    'referred_user_id' => $user->id,
                    'code_id' => $referralCode->id,
                ]);
            }

            return $user;
        });

        Auth::login($user);

        return redirect()->route('home');
    }
}
