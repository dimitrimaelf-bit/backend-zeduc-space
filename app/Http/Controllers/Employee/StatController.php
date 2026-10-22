<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function index()
    {
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();
        $orders = Order::whereBetween('created_at', [$start, $end])->get();
        $completed = $orders->where('statut', 'Terminée');
        $totalCommandes = $orders->count();
        $commandesTerminees = $completed->count();
        $ventesSemaine = $completed->sum('montant_total');
        $totalReclamations = Claim::whereBetween('created_at', [$start, $end])->count();
        $commandesParJour = [];
        foreach (range(6, 0) as $daysAgo) {
            $date = now()->subDays($daysAgo);
            $commandesParJour[$date->translatedFormat('D')] = Order::whereDate('created_at', $date->toDateString())->count();
        }
        return view('employe.statistiques', compact('totalCommandes','commandesTerminees','ventesSemaine','totalReclamations','commandesParJour'));
    }
}
