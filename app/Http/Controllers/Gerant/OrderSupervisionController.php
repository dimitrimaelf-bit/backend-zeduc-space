<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderSupervisionController extends Controller
{
    private const STATUSES = [
        'pending',
        'ready',
         'delivered',
        'cancelled',
    ];

    public function index(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $filters = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(self::STATUSES)],
        ]);

        $orders = Order::query()
            ->with([
                'user:id,first_name,last_name,phone',
                'items:id,order_id,item_name,quantity,line_total_fcfa',
                'payment:id,order_id,status,method,paid_at',
            ])
            ->when(
                isset($filters['status']),
                fn (Builder $query) => $query->where('status', $filters['status'])
            )
            ->orderByDesc('created_at')
            ->paginate(10);

        $summary = array_fill_keys(self::STATUSES, 0);
        $counts = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        foreach ($counts as $status => $count) {
            $summary[$status] = (int) $count;
        }

        
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(self::STATUSES)],
        ]);

        $order->update(['status' => $validated['status']]);

        
    }
}