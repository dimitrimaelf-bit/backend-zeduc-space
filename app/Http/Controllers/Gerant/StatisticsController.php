<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    private const ORDER_STATUSES = [
        'pending',
        'ready',
        'delivered',
        'cancelled',
    ];

    private const LOYALTY_TRANSACTION_TYPES = [
        'earn',
        'referral',
        'expire',
    ];

    private const REFERRAL_STATUSES = [
        'pending',
        'rewarded',
        'cancelled',
    ];

    public function index(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $paidPayments = Payment::query()->where('status', 'paid');
        $orderCounts = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $loyaltyTransactions = LoyaltyTransaction::query()
            ->selectRaw('type, COUNT(*) as transaction_count, SUM(points) as points_total')
            ->groupBy('type')
            ->get()
            ->keyBy('type');
        $referralCounts = Referral::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $ordersByStatus = array_fill_keys(self::ORDER_STATUSES, 0);
        foreach ($orderCounts as $status => $count) {
            $ordersByStatus[$status] = (int) $count;
        }

        $loyaltyByType = [];
        foreach (self::LOYALTY_TRANSACTION_TYPES as $type) {
            $transaction = $loyaltyTransactions->get($type);
            $loyaltyByType[$type] = [
                'transactions' => (int) ($transaction?->transaction_count ?? 0),
                'points_total' => (int) ($transaction?->points_total ?? 0),
            ];
        }

        $referralsByStatus = array_fill_keys(self::REFERRAL_STATUSES, 0);
        foreach ($referralCounts as $status => $count) {
            $referralsByStatus[$status] = (int) $count;
        }
    }
}