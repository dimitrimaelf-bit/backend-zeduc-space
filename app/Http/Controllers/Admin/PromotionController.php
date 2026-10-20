<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Evenement;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Affiche la liste des promotions et événements.
     */
    public function index()
    {
        // Récupération des promotions
        $promotions = class_exists(Promotion::class) 
            ? Promotion::latest()->get() 
            : collect();

        // Récupération des événements
        $evenements = class_exists(Evenement::class) 
            ? Evenement::latest()->get() 
            : collect();

        // Statistiques pour les cartes en haut
        $totalPromotionsActives = class_exists(Promotion::class) 
            ? Promotion::where('statut', 'actif')->count() 
            : 0;

        $evenementsAVenirCount = class_exists(Evenement::class) 
            ? Evenement::where('statut', 'planifie')->orWhere('statut', 'actif')->count() 
            : 0;

        $prochainEvenement = class_exists(Evenement::class) 
            ? Evenement::where('date_debut', '>=', now())->orderBy('date_debut', 'asc')->first() 
            : null;

        return view('admin.promotions.index', compact(
            'promotions',
            'evenements',
            'totalPromotionsActives',
            'evenementsAVenirCount',
            'prochainEvenement'
        ));
    }
}