<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\AppSetting;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Valide le panier et crée la commande en base de données.
     */
    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('etudiant.cart.index')->with('error', 'Votre panier est vide.');
        }

        // Validation du choix de l'étudiant (Livraison ou sur place)
        $request->validate([
            'service_mode' => 'required|in:delivery,on_site',
            'delivery_address' => 'required_if:service_mode,delivery'
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price_fcfa'] * $item['quantity'];
        }

        $fcfaPerPoint = max(1, (int) AppSetting::where('setting_key', 'loyalty_fcfa_per_point')->value('setting_value'));

        // Utilisation de DB::transaction pour s'assurer que tout s'enregistre, ou rien du tout en cas d'erreur
        DB::transaction(function () use ($cart, $request, $subtotal, $fcfaPerPoint) {
            $user = Auth::user();

            // 1. Création de l'entête de la commande
            $order = Order::create([
                'order_number' => 'CMD-' . strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'service_mode' => $request->service_mode,
                'delivery_address' => $request->service_mode === 'delivery' ? $request->delivery_address : null,
                'subtotal_fcfa' => $subtotal,
                'total_fcfa' => $subtotal, // Le calcul de la réduction par points viendra s'ajouter ici plus tard
                'status' => 'pending'
            ]);

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'manual_cash',
                'method' => 'cash',
                'status' => 'pending',
                'amount_fcfa' => $order->total_fcfa,
            ]);

            // 2. Figer les articles et leurs prix dans l'historique
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'unit_price_fcfa' => $item['price_fcfa'],
                    'quantity' => $item['quantity'],
                    'line_total_fcfa' => $item['price_fcfa'] * $item['quantity'],
                ]);
            }

            // Store the expected points on the order; a payment-confirmation flow must award them to the account.
            $pointsEarned = (int) floor($subtotal / $fcfaPerPoint);
            if ($pointsEarned > 0) {
                $order->update(['loyalty_points_earned' => $pointsEarned]);
            }
        });

        // 4. On vide le panier une fois la commande enregistrée
        Session::forget('cart');

        return redirect()->route('etudiant.menu.index')->with('success', 'Votre commande a été envoyée en cuisine !');
    }
}