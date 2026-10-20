<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Affiche la page d'accueil de l'étudiant : Menu, plats du jour et promotions.
     */
    public function index()
    {
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
        $categories = MenuCategory::with(['menuItems' => function ($query) {
                $query->where('is_available', true);
            }])
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('etudiant.menu.index', compact('promotions', 'dailySpecials', 'categories'));
    }
}