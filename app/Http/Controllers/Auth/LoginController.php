<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // affiche le formulaire de connexion
    public function create() {
        return view('auth.login');
    }

    /* valide les identifiants et utilise Auth::attempt()
    si ça échoue, une érreur est renvoyée
    si elle réussie, la session est regénérée et l'utilisateur se dirige vers les pages appropriées */
    public function store(Request $request)
{
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

    $user = Auth::user();

    // Redirection selon le code du rôle
    $targetRoute = match ($user->role?->code) {
        'admin'    => 'admin.dashboard',
        'employee' => 'dashboard', 
        'student'  => 'etudiant.accueil',
        default    => 'home',
    };

    return redirect()->intended(route($targetRoute));
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
