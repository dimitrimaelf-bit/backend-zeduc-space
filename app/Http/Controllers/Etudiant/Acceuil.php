<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Solde de points affiché dans le bandeau ("12 pts")
        $balance = (int) LoyaltyAccount::whereKey($user->id)->value('balance_points');

        // Menu du jour : 4 plats disponibles marqués "plat du jour"
        $dailySpecials = MenuItem::where('is_available', true)
            ->where('is_daily_special', true)
            ->orderBy('name')
            ->limit(4)
            ->get();

        // Promotions en cours : actives et dont les dates encadrent aujourd'hui
        // (une date NULL = pas de limite)
        $promotions = Promotion::where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->latest('created_at')
            ->limit(2)
            ->get();

        // Top 10 des clients : ?periode=semaine | mois | tout
        $periode = in_array($request->query('periode'), ['semaine', 'mois'], true)
            ? $request->query('periode') : 'tout';

        $depuis = match ($periode) {
            'semaine' => now()->subWeek(),
            'mois'    => now()->subMonth(),
            default   => null,
        };

        $topClients = User::where('role_id', 2)
            // withCount crée la colonne "commandes_terminees" sans charger les commandes
            ->withCount(['orders as commandes_terminees' => function ($q) use ($depuis) {
                $q->where('status', 'completed');
                if ($depuis) {
                    $q->where('created_at', '>=', $depuis);
                }
            }])
            ->having('commandes_terminees', '>', 0)
            ->orderByDesc('commandes_terminees')
            ->orderBy('first_name')               // départage les ex-aequo
            ->limit(10)
            ->get(['id', 'first_name', 'last_name']); // jamais d'email des autres étudiants

        return view('etudiant.accueil', compact('user', 'balance', 'dailySpecials', 'promotions', 'topClients', 'periode'));
    }
}