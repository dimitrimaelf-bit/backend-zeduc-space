<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Claim;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Espace Administrateur (role_id = 4)
        if ($user->role_id == 4) {
            return $this->adminDashboard();
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
        return redirect()->route('student.home');
    }

    /**
     * Stats Administrateur : Vue globale, fidélité & paramétrage
     */
    private function adminDashboard()
    {
        $stats = [
            'total_sales' => class_exists(Order::class) ? Order::sum('total_amount') : 0,
            'total_orders' => class_exists(Order::class) ? Order::count() : 0,
            'total_employees' => User::where('role_id', 2)->count(),
            'pending_claims' => class_exists(Claim::class) ? Claim::where('status', 'pending')->count() : 0,
        ];

        $loyaltyStats = [
            'total_points_awarded' => User::sum('loyalty_points') ?? 0,
            'total_referrals'      => DB::getSchemaBuilder()->hasTable('referrals') 
                ? DB::table('referrals')->where('status', 'completed')->count() 
                : 0,
        ];

        return view('admin.dashboard', compact('stats', 'loyaltyStats'));
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

        $topClients = $query->groupBy('users.id', 'users.name')
            ->orderByDesc('orders_count')
            ->limit(10)
            ->get();

        return response()->json($topClients);
    }
}