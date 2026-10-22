<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    // affiche le formulaire de connexion
    public function create() {
        return view('auth.login');
    }

    /* valide les identifiants et utilise Auth::attempt()
    si ça échoue, une érreur est renvoyée
    si elle réussie, la session est regénérée et l'utilisateur se dirige vers le tableau de bord */
    public function store(Request $request) {

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => "Identifiants incorrects.",
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('etudiant.accueil'));
    }

    /*déconnecter l'utilisateur, invalide la session
    et regénère un jeton de sécurité CSRF  */
    public function destroy(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
