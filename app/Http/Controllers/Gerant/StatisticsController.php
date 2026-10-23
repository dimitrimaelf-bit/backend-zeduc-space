<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\OrderItem;
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
        'redeem',
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

        // Classe les plats par quantité vendue en conservant les articles supprimés du menu.
        $topPlats = OrderItem::query()
            ->leftJoin('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->selectRaw('COALESCE(menu_items.name, order_items.item_name) as name')
            ->selectRaw('COALESCE(menu_items.price_fcfa, order_items.unit_price_fcfa) as price_fcfa')
            ->selectRaw('menu_items.image_url as image_url')
            ->selectRaw('SUM(order_items.quantity) as commandes_count')
            ->groupBy(
                'order_items.menu_item_id',
                'order_items.item_name',
                'order_items.unit_price_fcfa',
                'menu_items.name',
                'menu_items.price_fcfa',
                'menu_items.image_url'
            )
            ->orderByDesc('commandes_count')
            ->limit(3)
            ->get();

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

        $discountedOrders = (int) Order::query()->where('discount_fcfa', '>', 0)->count();
        $referredUserIds = Referral::query()
            ->where('status', '!=', 'cancelled')
            ->distinct()
            ->pluck('referred_user_id');
        $repeatPurchasingReferrals = $referredUserIds->isEmpty()
            ? 0
            : (int) Order::query()
                ->whereIn('user_id', $referredUserIds)
                ->whereIn('status', ['delivered', 'completed'])
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) >= 2')
                ->get()
                ->count();

        $rewards = [
            'loyalty_redemptions' => $loyaltyByType['redeem']['transactions'],
            'discounted_orders' => $discountedOrders,
            'referral_repeat_purchase_rate' => $referredUserIds->isNotEmpty()
                ? (int) round(($repeatPurchasingReferrals / $referredUserIds->count()) * 100)
                : 0,
        ];

        $ordersByChannel = Order::query()
            ->selectRaw('service_mode, COUNT(*) as aggregate')
            ->groupBy('service_mode')
            ->pluck('aggregate', 'service_mode');
        $channelTotal = (int) $ordersByChannel->sum();
        $channels = [];
        foreach (['on_site', 'delivery'] as $serviceMode) {
            $count = (int) ($ordersByChannel[$serviceMode] ?? 0);
            $channels[$serviceMode] = [
                'count' => $count,
                'percentage' => $channelTotal > 0
                    ? (int) round(($count / $channelTotal) * 100)
                    : 0,
            ];
        }

        return view('Gerant.Stats', compact(
            'sales',
            'orders',
            'loyalty',
            'referrals',
            'topPlats',
            'rewards',
            'channels'
        ));
    }
}