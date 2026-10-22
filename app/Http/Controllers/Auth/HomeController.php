<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    // retourne la vue de la page d'accueil après l'authentification
    public function index()
    {
        return view('home');
    }
}