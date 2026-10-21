<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    /**
     * Affiche le solde et l'historique des points de fidélité.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Solde de points pour la barre de navigation
        $balance = $user ? (int) LoyaltyAccount::whereKey($user->id)->value('balance_points') : 0;
        
        // 1. Récupération du compte fidélité
        $account = LoyaltyAccount::where('user_id', $user->id)->first();
        
        // 2. Récupération de l'historique complet
        $transactions = LoyaltyTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // 3. Récupération du code de parrainage actif de l'étudiant
        $referralCode = $user->referralCode()->where('is_active', true)->first();

        // 4. Récupération de la liste des filleuls (parrainages)
        $referrals = Referral::where('referrer_user_id', $user->id)
            ->with(['referred', 'code'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('etudiant.loyalty.index', compact('account', 'transactions', 'user', 'balance', 'referralCode', 'referrals'));
    }
}