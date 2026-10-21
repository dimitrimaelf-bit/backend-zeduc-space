<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class EmployeeOrderController extends Controller
{
    /**
     * Afficher les commandes.
     */
    public function index()
    {
        $orders = Order::with(['user', 'items'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Liste des commandes.',
            'data' => $orders,
        ]);
    }

    /**
     * Afficher les détails d'une commande.
     */
    public function show(int $id)
    {
        $order = Order::with(['user', 'items', 'payment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Valider une commande en attente.
     */
    public function validateOrder(int $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut pas être validée.',
            ], 422);
        }

        $order->status = 'confirmed';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Commande validée avec succès.',
            'data' => $order,
        ]);
    }

    /**
     * Marquer une commande prête comme servie.
     */
    public function markAsServed(int $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'ready') {
            return response()->json([
                'success' => false,
                'message' => 'La commande doit être prête avant d’être servie.',
            ], 422);
        }

        $order->status = 'served';
        $order->completed_at = now();
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Commande marquée comme servie.',
            'data' => $order,
        ]);
    }
}
