<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    /**
     * Affiche la page du menu de l'étudiant.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Récupération du solde de points de fidélité
        $balance = $user ? (int) \App\Models\LoyaltyAccount::whereKey($user->id)->value('balance_points') : 0;

        // Récupération du panier depuis la session
        $cart = Session::get('cart', []);

        // 1. Récupérer les promotions actives
        $promotions = Promotion::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->get();

        // 2. Récupérer les plats du jour disponibles
        $dailySpecials = MenuItem::where('is_daily_special', true)
            ->where('is_available', true)
            ->get();

        // 3. Récupérer le menu classé par catégories (uniquement les articles disponibles)
        $categories = MenuCategory::with(['items' => function ($query) {
        $query->where('is_available', true);
       }])
        ->where('is_active', true)
        ->where('name', '!=', 'Plats Principaux') // <-- On exclut "Plats Principaux" d'ici
        ->orderBy('sort_order', 'asc')
        ->get();

        return view('etudiant.menu.index', compact('promotions', 'dailySpecials', 'categories', 'user', 'balance', 'cart'));
    }
}