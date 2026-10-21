<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class EmployeeStatController extends Controller
{
    /**
     * Statistiques des sept derniers jours.
     */
    public function weeklyStats()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $orders = Order::whereBetween('created_at', [
            $startDate,
            $endDate,
        ])->get();

        $completedOrders = $orders
            ->whereIn('status', ['served', 'completed']);
    }
}
