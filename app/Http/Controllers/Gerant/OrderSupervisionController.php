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
        'completed',
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

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Attribution des points de fidélité lors de la finalisation de la commande
        // Les points ne sont attribués QUE lorsque la commande passe en 'delivered' ou 'completed'
        // et que les points n'ont pas encore été attribués (loyalty_points_earned == 0)
        if (($newStatus === 'delivered' || $newStatus === 'completed') && $order->loyalty_points_earned == 0) {
            // Récupération du compte de fidélité de l'étudiant
            $loyaltyAccount = \App\Models\LoyaltyAccount::firstOrCreate(
                ['user_id' => $order->user_id],
                ['balance_points' => 0]
            );

            // Attribution de 1 point de fidélité
            $loyaltyAccount->increment('balance_points', 1);

            // Mise à jour de la commande pour indiquer que les points ont été attribués
            $order->update(['loyalty_points_earned' => 1]);

            // Création de l'historique de la transaction
            \App\Models\LoyaltyTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'type' => 'earn',
                'points' => 1,
                'description' => "Point de fidélité validé pour la commande {$order->order_number}",
            ]);
        }

        $order->update(['status' => $validated['status']]);

    }
}