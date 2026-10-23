<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\Promotion;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Affiche la page dédiée aux Événements et Promotions (du jour et à venir)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $balance = $user ? (int) LoyaltyAccount::whereKey($user->id)->value('balance_points') : 0;

        // Filtre par catégorie : 'tout', 'promotion' ou 'event'
        $filtre = in_array($request->query('categorie'), ['promotion', 'event'], true)
            ? $request->query('categorie')
            : 'tout';

        $baseQuery = Promotion::where('is_active', true)
            ->when($filtre !== 'tout', fn ($q) => $q->where('type', $filtre));

        // 1. Offres et événements en cours aujourd'hui
        $offresDuJour = (clone $baseQuery)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('starts_at', 'asc')
            ->get();

        // 2. Offres et événements planifiés pour les jours suivants
        $offresAVenir = (clone $baseQuery)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc')
            ->get();

        return view('etudiant.evenements.index', compact(
            'user',
            'balance',
            'offresDuJour',
            'offresAVenir',
            'filtre'
        ));
    }
}
