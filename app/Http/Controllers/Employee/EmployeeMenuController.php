<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class EmployeeMenuController extends Controller
{
    /**
     * Afficher les plats et leurs catégories.
     */
    public function index()
    {
        $items = MenuItem::with('category')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Modifier la disponibilité d'un plat.
     */
    public function updateAvailability(Request $request, int $id)
    {
        $validated = $request->validate([
            'is_available' => ['required', 'boolean'],
        ]);

        $item = MenuItem::findOrFail($id);
        $item->is_available = $validated['is_available'];
        $item->save();

        return response()->json([
            'success' => true,
            'message' => $item->is_available
                ? 'Le plat est maintenant disponible.'
                : 'Le plat est maintenant indisponible.',
            'data' => $item,
        ]);
    }

    /**
     * Désigner un plat du jour.
     */
    public function updatePlatDuJour(int $id)
    {
        $item = MenuItem::findOrFail($id);

        // Cette opération suppose qu'un seul plat du jour est actif à la fois.
        MenuItem::query()->update(['is_daily_special' => false]);

        $item->is_daily_special = true;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Le plat du jour a été modifié.',
            'data' => $item,
        ]);
    }
}
