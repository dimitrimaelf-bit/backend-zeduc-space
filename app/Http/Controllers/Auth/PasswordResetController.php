<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    // affiche le formulaire "mot de passe oublié"
    public function request() {
        return view('auth.forgot-password');
    }

    // valide l"email et renvoie le lien de réinitialisation
    public function email(Request $request)
    {
        $request->validate(["email" => ['required', 'string', 'max:255', 'email']]);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // affiche le formulaire pour créer un nouveau mot de passe,
    //en passant le token et l'email dans la vue
    public function reset(string $token) {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    /* vérifie les données, utilise Password::reset()
    si le token est valide, la fonction anonyme met
    à jour le mot de passe de l'utilisateur */
    public function update(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password,
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('etudiant.accueil')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
