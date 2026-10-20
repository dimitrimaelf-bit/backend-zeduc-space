<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    /**
     * Liste toutes les réclamations de l'étudiant.
     */
    public function index()
    {
        // "Eager loading" de la commande associée pour optimiser la requête
        $claims = Claim::with('order')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('etudiant.reclamations.index', compact('claims'));
    }

    /**
     * Affiche le formulaire pour créer une nouvelle réclamation.
     */
    public function create()
    {
        // On récupère uniquement les commandes terminées ou livrées de cet étudiant
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['delivered', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('etudiant.reclamations.create', compact('orders'));
    }

    /**
     * Enregistre la réclamation dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'subject' => 'required|string|max:150',
            'description' => 'required|string'
        ]);

        // Sécurité : Vérifier que la commande appartient bien à l'étudiant connecté
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Création de la réclamation avec le statut "open" par défaut
        Claim::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'open'
        ]);

        return redirect()->route('etudiant.reclamations.index')
            ->with('success', 'Votre réclamation a été transmise avec succès aux employés.');
    }
}