<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\LoyaltyAccount;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    /**
     * Affiche l'historique des commandes, le formulaire de réclamation et le suivi.
     */
    public function index()
    {
        $user = Auth::user();
        $balance = (int) LoyaltyAccount::whereKey($user->id)->value('balance_points');

        // 1. Toutes les commandes de l'étudiant (Historique)
        $orders = Order::with('items')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Toutes les réclamations de l'étudiant (Suivi)
        $claims = Claim::with('order')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('etudiant.reclamations.index', compact('user', 'balance', 'orders', 'claims'));
    }

    /**
     * Redirige vers la page principale si on appelle /reclamations/nouveau en GET.
     */
    public function create()
    {
        return redirect()->route('etudiant.reclamations.index');
    }

    /**
     * Enregistre la réclamation dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id'    => 'required|exists:orders,id',
            'subject'     => 'nullable|string|max:150',
            'description' => 'required|string'
        ]);

        // Sécurité : Vérifier que la commande appartient bien à l'étudiant connecté
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Création de la réclamation avec le statut "open" par défaut
        Claim::create([
            'order_id'    => $order->id,
            'user_id'     => Auth::id(),
            'subject'     => $request->subject ?: 'Problème sur la commande #' . $order->order_number,
            'description' => $request->description,
            'status'      => 'open'
        ]);

        return redirect()->route('etudiant.reclamations.index')
            ->with('success', 'Votre réclamation a été transmise avec succès aux employés.');
    }
}