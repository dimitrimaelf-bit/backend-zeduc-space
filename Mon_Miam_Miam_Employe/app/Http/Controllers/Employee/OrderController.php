<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user','items'])->latest();
        if ($request->filled('search')) {
            $term = $request->string('search')->toString();
            $query->where(function ($q) use ($term) {
                $q->where('id', 'like', "%{$term}%")
                  ->orWhere('client_nom', 'like', "%{$term}%");
            });
        }
        if ($request->filled('statut')) $query->where('statut', $request->input('statut'));
        $commandes = $query->paginate(15)->withQueryString();
        return view('employe.commandes.index', compact('commandes'));
    }

    public function show(Order $commande)
    {
        $commande->load(['user','items']);
        return view('employe.commandes.show', compact('commande'));
    }

    public function update(Request $request, Order $commande)
    {
        $validated = $request->validate(['statut' => ['required','in:En attente,En préparation,Prête,Terminée,Annulée']]);
        $commande->statut = $validated['statut'];
        if ($validated['statut'] === 'Terminée' && empty($commande->completed_at)) $commande->completed_at = now();
        $commande->save();
        return redirect()->route('employe.commandes.show', $commande)->with('success', 'Statut de la commande mis à jour.');
    }
}
