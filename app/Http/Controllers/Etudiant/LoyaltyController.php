<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    /**
     * Affiche le solde et l'historique des points de fidélité.
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Récupération du compte fidélité (qui a été créé automatiquement par votre Trigger SQL)
        $account = LoyaltyAccount::where('user_id', $user->id)->first();
        
        // 2. Récupération de l'historique complet (Livre de comptes)
        $transactions = LoyaltyTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('etudiant.loyalty.index', compact('account', 'transactions'));
    }
}