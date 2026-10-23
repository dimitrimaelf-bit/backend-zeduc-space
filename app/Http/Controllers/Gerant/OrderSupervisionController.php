<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderSupervisionController extends Controller
{
    // États acceptés par le filtre de supervision.
    private const STATUSES = [
        'pending',
        'confirmed',
        'preparing',
        'ready',
        'delivered',
        'completed',
        'cancelled',
    ];

    public function index(Request $request)
    {
        // Réserve la supervision des commandes aux gérants.
        abort_unless((int) $request->user()?->role_id === 3, 403);

        // Vérifie que l'état demandé fait partie des états déclarés ci-dessus.
        $filters = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(self::STATUSES)],
        ]);

        // Charge les relations nécessaires à l'affichage et applique le filtre éventuel.
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

        // Prépare les compteurs des états connus, y compris ceux sans commande.
        $summary = array_fill_keys(self::STATUSES, 0);
        $counts = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        // Remplit les compteurs avec les valeurs calculées par la base de données.
        foreach ($counts as $status => $count) {
            $summary[$status] = (int) $count;
        }

       $currentTab = match ($filters['status'] ?? null) {
           'ready' => 'pretes',
           'delivered', 'completed' => 'livrees',
           default => 'supervision',
       };

       return view('Gerant.Supervision', [
           'orders' => $orders,
           'summary' => $summary,
           'currentTab' => $currentTab,
           'commandesEnAttente' => $summary['pending'] + $summary['confirmed'] + $summary['preparing'],
           'commandesPretes' => $summary['ready'],
           'commandesLivrees' => $summary['delivered'] + $summary['completed'],
       ]);
    }

    /**
     * Met à jour l'état d'une commande après validation des droits et de la valeur.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Seul un gérant est autorisé à modifier l'état d'une commande.
        abort_unless((int) $request->user()?->role_id === 3, 403);

        // Refuse tout état qui n'est pas prévu dans la liste autorisée.
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(self::STATUSES)],
        ]);

        // Enregistre l'état demandé sur la commande ciblée.
        $order->update(['status' => $validated['status']]);

        return redirect()
            ->route('gerant.orders.index')
            ->with('status', 'Le statut de la commande a été mis à jour.');
    }
}