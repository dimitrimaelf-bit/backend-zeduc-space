<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Affiche le contenu du panier.
     */
    public function index()
    {
        $cart = \Illuminate\Support\Facades\Session::get('cart', []);
        $total = $this->calculateTotal($cart);
        
        $user = auth()->user();
        // On récupère le solde, ou 0 si le compte fidélité n'existe pas encore
        $balance = $user ? (int) \App\Models\LoyaltyAccount::whereKey($user->id)->value('balance_points') : 0;

        return view('etudiant.cart.index', compact('cart', 'total', 'balance'));
    }

    /**
     * Ajoute un plat au panier (Appelé via AJAX ou formulaire).
     */
    public function add(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = MenuItem::findOrFail($request->menu_item_id);
        
        if (!$item->is_available) {
            return back()->with('error', 'Désolé, ce plat est actuellement épuisé.');
        }

        $cart = Session::get('cart', []);

        // Si le plat est déjà dans le panier, on incrémente la quantité
        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $request->quantity;
        } else {
            // Sinon, on l'ajoute
            $cart[$item->id] = [
                'id' => $item->id,
                'name' => $item->name,
                'price_fcfa' => $item->price_fcfa,
                'quantity' => $request->quantity,
                'image_url' => $item->image_url,
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', $item->name . ' a été ajouté à votre miam miam panier !');
    }

    /**
     * Retire un article du panier.
     */
    public function remove(Request $request)
    {
        $cart = Session::get('cart', []);
        
        $itemId = $request->input('item_id') ?? $request->input('menu_item_id');

        if (isset($cart[$itemId])) {
            // Si la quantité est supérieure à 1, on décrémente
            if ($cart[$itemId]['quantity'] > 1) {
                $cart[$itemId]['quantity']--;
            } else {
                // Sinon, on supprime uniquement cet article du panier
                unset($cart[$itemId]);
            }
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Panier mis à jour.');
    }

    /**
     * Calcule le total du panier en FCFA.
     */
    private function calculateTotal(array $cart): int
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price_fcfa'] * $item['quantity'];
        }
        return $total;
    }
}