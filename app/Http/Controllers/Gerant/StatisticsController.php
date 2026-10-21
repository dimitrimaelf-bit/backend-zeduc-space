<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralCode;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    // Valeurs affichées dans le récapitulatif des commandes.
    private const ORDER_STATUSES = [
        'pending',
        'ready',
        'delivered',
        'cancelled',
    ];

    // Types d'opérations de fidélité présentés dans les statistiques.
    private const LOYALTY_TRANSACTION_TYPES = [
        'earn',
        'referral',
        'expire',
    ];

    // États des parrainages présentés dans les statistiques.
    private const REFERRAL_STATUSES = [
        'pending',
        'rewarded',
        'cancelled',
    ];

    public function index(Request $request)
    {
        // Seul un gérant peut consulter les chiffres globaux de l'activité.
        abort_unless((int) $request->user()?->role_id === 3, 403);

        // Prépare les paiements encaissés pour calculer le chiffre d'affaires et leur nombre.
        $paidPayments = Payment::query()->where('status', 'paid');

        // Regroupe les commandes et les opérations de fidélité par état ou type.
        $orderCounts = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $loyaltyTransactions = LoyaltyTransaction::query()
            ->selectRaw('type, COUNT(*) as transaction_count, SUM(points) as points_total')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Compte les parrainages dans chaque état.
        $referralCounts = Referral::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        // Initialise tous les états connus à zéro, puis y reporte les résultats présents.
        $ordersByStatus = array_fill_keys(self::ORDER_STATUSES, 0);
        foreach ($orderCounts as $status => $count) {
            $ordersByStatus[$status] = (int) $count;
        }

        // Prépare pour chaque type le nombre de transactions et le total de points.
        $loyaltyByType = [];
        foreach (self::LOYALTY_TRANSACTION_TYPES as $type) {
            $transaction = $loyaltyTransactions->get($type);
            $loyaltyByType[$type] = [
                'transactions' => (int) ($transaction?->transaction_count ?? 0),
                'points_total' => (int) ($transaction?->points_total ?? 0),
            ];
        }

        // Prépare le récapitulatif des parrainages, y compris les états sans résultat.
        $referralsByStatus = array_fill_keys(self::REFERRAL_STATUSES, 0);
        foreach ($referralCounts as $status => $count) {
            $referralsByStatus[$status] = (int) $count;
        }

        $sales = [
            'paid_total_fcfa' => (int) (clone $paidPayments)->sum('amount_fcfa'),
            'paid_transactions' => (int) (clone $paidPayments)->count(),
        ];
        $sales['average_fcfa'] = $sales['paid_transactions'] > 0
            ? (int) round($sales['paid_total_fcfa'] / $sales['paid_transactions'])
            : 0;

        $orders = [
            'total' => (int) Order::query()->count(),
            'by_status' => $ordersByStatus,
        ];
        $loyalty = [
            'accounts' => (int) LoyaltyAccount::query()->count(),
            'balance_points_total' => (int) LoyaltyAccount::query()->sum('balance_points'),
            'by_type' => $loyaltyByType,
        ];
        $referrals = [
            'active_codes' => (int) ReferralCode::query()->where('is_active', true)->count(),
            'by_status' => $referralsByStatus,
        ];

        return view('Gerant.Stats', compact('sales', 'orders', 'loyalty', 'referrals'));
    }
}