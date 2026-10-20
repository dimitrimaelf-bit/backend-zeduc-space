<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    /**
     * Affiche la page Commander : Plats, catégories, promotions et panier intégré.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $balance = (int) LoyaltyAccount::whereKey($user->id)->value('balance_points');

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

        // 3. Récupérer le menu classé par catégories (utilise la relation 'items' de MenuCategory)
        $categories = MenuCategory::with(['items' => function ($query) {
                $query->where('is_available', true);
            }])
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 4. Récupérer le panier en session pour l'afficher en bas de page
        $cart = Session::get('cart', []);

        return view('etudiant.menu.index', compact(
            'user',
            'balance',
            'promotions',
            'dailySpecials',
            'categories',
            'cart'
        ));
    }
}