<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Payment;
use App\Models\LoyaltyTransaction;
use App\Models\Referral;
use App\Models\User;
use App\Models\Claim;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Espace Administrateur (role_id = 4)
        if ($user->role_id == 4) {
            return $this->adminDashboard($request);
        }

        // 2. Espace Employé (role_id = 2)
        if ($user->role_id == 2) {
            return $this->employeeDashboard();
        }

        // 3. Espace Gérant (role_id = 3)
        if ($user->role_id == 3) {
            return $this->gerantDashboard();
        }

        // 4. Espace Étudiant (role_id = 1)
        return redirect()->route('etudiant.accueil');
    }

    public function stats(Request $request)
    {
        return $this->adminDashboard($request, 'admin.statistics');
    }

    /**
     * Stats Administrateur : Vue globale, fidélité & paramétrage
     */
    private function adminDashboard(Request $request, string $view = 'admin.dashboard')
    {
        $period = $request->query('period', 'week');
        abort_unless(in_array($period, ['day', 'week', 'month'], true), 404);

        $now = now();
        [$start, $end, $previousStart, $previousEnd, $periodLabel] = match ($period) {
            'day' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
                "Aujourd'hui",
            ],
            'month' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
                'Ce mois-ci',
            ],
            default => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
                $now->copy()->subWeek()->startOfWeek(),
                $now->copy()->subWeek()->endOfWeek(),
                'Cette semaine',
            ],
        };

        $salesFor = fn ($from, $to) => Payment::query()
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$from, $to]);
        $ordersFor = fn ($from, $to) => Order::query()
            ->whereBetween('created_at', [$from, $to]);
        $sales = (int) $salesFor($start, $end)->sum('amount_fcfa');
        $previousSales = (int) $salesFor($previousStart, $previousEnd)->sum('amount_fcfa');
        $orders = (int) $ordersFor($start, $end)->count();
        $previousOrders = (int) $ordersFor($previousStart, $previousEnd)->count();
        $orderStatuses = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $change = fn (int $current, int $previous) => $previous > 0
            ? round((($current - $previous) / $previous) * 100, 1)
            : null;

        $stats = [
            'total_sales' => $sales,
            'total_orders' => $orders,
            'total_employees' => User::where('role_id', 2)->where('is_active', true)->count(),
            'pending_claims' => Claim::where('status', 'open')->count(),
        ];

        $loyaltyStats = [
            'total_points_awarded' => (int) LoyaltyTransaction::whereIn('type', ['earn', 'referral', 'adjust'])->sum('points'),
            'period_points_awarded' => (int) LoyaltyTransaction::whereIn('type', ['earn', 'referral', 'adjust'])
                ->whereBetween('created_at', [$start, $end])->sum('points'),
            'total_referrals' => Referral::where('status', 'rewarded')->count(),
            'period_referrals' => Referral::where('status', 'rewarded')
                ->whereBetween('rewarded_at', [$start, $end])->count(),
        ];

        $chartDays = $view === 'admin.statistics' ? 29 : 6;
        $chartStart = now()->subDays($chartDays)->startOfDay();
        $chartEnd = now()->endOfDay();
        $salesByDay = Payment::query()
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$chartStart, $chartEnd])
            ->selectRaw('DATE(paid_at) as day, SUM(amount_fcfa) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
        $ordersByDay = Order::query()
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
        $dailyActivity = collect(range($chartDays, 0))->map(function ($daysAgo) use ($salesByDay, $ordersByDay) {
            $date = now()->subDays($daysAgo)->toDateString();

            return [
                'label' => now()->subDays($daysAgo)->format('d/m'),
                'sales' => (int) ($salesByDay[$date] ?? 0),
                'orders' => (int) ($ordersByDay[$date] ?? 0),
            ];
        });
        $maxDailySales = max(1, (int) $dailyActivity->max('sales'));
        $chartSalesTotal = (int) $dailyActivity->sum('sales');
        $chartOrdersTotal = (int) $dailyActivity->sum('orders');
        $recentOrders = Order::with('user:id,first_name,last_name')->latest()->limit(6)->get();
        $salesChange = $change($sales, $previousSales);
        $ordersChange = $change($orders, $previousOrders);
        $statsRouteName = $view === 'admin.statistics' ? 'admin.stats' : 'admin.dashboard';

        return view($view, compact(
            'stats', 'loyaltyStats', 'period', 'periodLabel', 'salesChange',
            'ordersChange', 'dailyActivity', 'maxDailySales', 'chartSalesTotal',
            'chartOrdersTotal', 'recentOrders',
            'orderStatuses', 'statsRouteName'
        ));
    }
    //Stats Gérant : Supervision globale & réclamations
    private function gerantDashboard()
    {
        $stats = [
            'daily_orders' => class_exists(Order::class) ? Order::whereDate('created_at', now()->today())->count() : 0,
            'total_loyalty_points' => User::sum('loyalty_points') ?? 0,
            'claims_to_validate' => class_exists(Claim::class) ? Claim::where('status', 'employee_processed')->count() : 0,
        ];

        $loyaltyStats = [
            'total_points_awarded' => User::sum('loyalty_points') ?? 0,
            'total_referrals'      => DB::getSchemaBuilder()->hasTable('referrals') 
                ? DB::table('referrals')->where('status', 'completed')->count() 
                : 0,
        ];

        return view('dashboards.gerant', compact('stats', 'loyaltyStats'));
    }

    //Stats Employé : Ventes de la semaine, commandes en cours, réclamations

    private function employeeDashboard()
    {
        $weeklySales = class_exists(Order::class) 
            ? Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->selectRaw('COALESCE(SUM(total_amount), 0) as total, COUNT(id) as count')
                ->first()
            : (object)['total' => 0, 'count' => 0];

        $activeOrders = class_exists(Order::class) 
            ? Order::whereIn('status', ['pending', 'preparing'])->get() 
            : collect();

        $claims = class_exists(Claim::class) 
            ? Claim::where('status', 'pending')->get() 
            : collect();

        return view('dashboards.employee', compact('weeklySales', 'activeOrders', 'claims'));
    }

    // API AJAX : Visualisation des 10 meilleurs clients (jour, semaine, mois)
     
    public function getTopClients(Request $request)
    {
        $filter = $request->get('filter', 'month');

        $query = User::select('users.id', 'users.name', DB::raw('COUNT(orders.id) as orders_count'))
            ->join('orders', 'users.id', '=', 'orders.user_id');

        if ($filter === 'day') {
            $query->whereDate('orders.created_at', now()->today());
        } elseif ($filter === 'week') {
            $query->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('orders.created_at', now()->month)
                  ->whereYear('orders.created_at', now()->year);
        }

        $topClients = $query->groupBy('users.id', 'users.last_name')
            ->orderByDesc('orders_count')
            ->limit(10)
            ->get();

        return response()->json($topClients);
    }
}