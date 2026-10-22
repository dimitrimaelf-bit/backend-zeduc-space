<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Claim;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $commandesEnAttente = Order::whereIn('statut', ['En attente', 'En préparation', 'Prête'])->count();
        $commandesTerminees = Order::where('statut', 'Terminée')->where('created_at', '>=', $today)->count();
        $reclamationsOuvertes = Claim::whereIn('statut', ['Ouverte', 'En cours'])->count();
        $ventesSemaine = Order::where('statut', 'Terminée')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('montant_total');
        $commandesRecentes = Order::with('user')->latest()->take(6)->get();
        return view('employe.dashboard', compact('commandesEnAttente','commandesTerminees','reclamationsOuvertes','ventesSemaine','commandesRecentes'));
    }
}
