<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        // Pour l'instant, on affiche un texte simple pour débloquer la page.
        // Plus tard, tu pourras remplacer cette ligne par : return view('admin.dashboard');
        return "Bienvenue sur le tableau de bord Administrateur !";
    }
}