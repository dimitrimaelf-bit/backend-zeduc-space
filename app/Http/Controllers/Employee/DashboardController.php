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

        $commandesEnAttente = Order::whereIn('status', ['pending', 'preparing', 'ready'])->count();
        $commandesTerminees = Order::where('status', 'completed')->where('created_at', '>=', $today)->count();
        $reclamationsOuvertes = Claim::whereIn('status', ['open', 'in_progress'])->count();
        
    
        $ventesSemaine = Order::where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_fcfa');

        $commandesRecentes = Order::with('user')->latest()->take(6)->get();

        return view('employe.dashboard', compact(
            'commandesEnAttente',
            'commandesTerminees',
            'reclamationsOuvertes',
            'ventesSemaine',
            'commandesRecentes'
        ));
    }
}