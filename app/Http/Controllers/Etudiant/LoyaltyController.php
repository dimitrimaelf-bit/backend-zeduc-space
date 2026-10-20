<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Referral;
use App\Models\ReferralCode;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    /**
     * Affiche la page combinée : Fidélité et Parrainage.
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Solde de points
        $account = LoyaltyAccount::where('user_id', $user->id)->first();
        $balance = $account ? (int) $account->balance_points : 0;
        
        // 2. Historique des transactions de points (avec la commande liée)
        $transactions = LoyaltyTransaction::with('order')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Code de parrainage actif de l'étudiant
        $referralCode = ReferralCode::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        // 4. Liste des filleuls parrainés par cet étudiant
        $referrals = Referral::with(['referred.orders'])
            ->where('referrer_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('etudiant.loyalty.index', compact(
            'user',
            'balance',
            'account',
            'transactions',
            'referralCode',
            'referrals'
        ));
    }
}