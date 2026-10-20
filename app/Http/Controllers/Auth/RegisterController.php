<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $user = User::create($request->validated());
        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
