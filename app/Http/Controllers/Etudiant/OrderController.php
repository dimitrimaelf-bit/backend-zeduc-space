<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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

            $request->validate([
            'service_mode' => 'required|in:delivery,on_site',
            'delivery_address' => 'required_if:service_mode,delivery',
            'payment_method'   => 'required|in:mobile_money,cash',
        ]);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price_fcfa'] * $item['quantity'];
        }

        DB::transaction(function () use ($cart, $request, $subtotal) {
            $user = Auth::user();

            // 1. Récupération du compte de fidélité
            $loyaltyAccount = \App\Models\LoyaltyAccount::firstOrCreate(
                ['user_id' => $user->id],
                ['balance_points' => 0]
            );

            // 2. Gestion de la réduction (5 points = 1000 FCFA de réduction)
            $discount_fcfa = 0;
            $pointsUsed = 0;

            // On vérifie si l'étudiant a coché la case ET s'il a au moins 5 points
            if ($request->has('use_points') && $loyaltyAccount->balance_points >= 5) {
                // On calcule combien de blocs de 5 points il possède
                $blocsDe5 = floor($loyaltyAccount->balance_points / 5);
                $discount_fcfa = $blocsDe5 * 1000;
                
                // Si la réduction dépasse le prix de la commande, on ajuste
                if ($discount_fcfa > $subtotal) {
                    $discount_fcfa = $subtotal;
                    $pointsUsed = ceil($subtotal / 1000) * 5; 
                } else {
                    $pointsUsed = $blocsDe5 * 5;
                }

                // On déduit les points utilisés de son compte
                $loyaltyAccount->decrement('balance_points', $pointsUsed);
            }

            // Calcul du total final à payer après déduction
            $total_fcfa = $subtotal - $discount_fcfa;

            // 3. Gain de points : +1 point pour avoir passé la commande
            $pointsEarned = 1;
            $loyaltyAccount->increment('balance_points', $pointsEarned);

            // 4. Création de l'entête de la commande
            $order = Order::create([
                'order_number' => 'CMD-' . strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'service_mode' => $request->service_mode,
                'delivery_address' => $request->service_mode === 'delivery' ? $request->delivery_address : null,
                'subtotal_fcfa' => $subtotal,
                'discount_fcfa' => $discount_fcfa,
                'total_fcfa' => $total_fcfa,
                'loyalty_points_used' => $pointsUsed,
                'loyalty_points_earned' => $pointsEarned, // Enregistrement du point gagné
                'status' => 'pending'
            ]);

            // 5. Enregistrement des plats
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

            // 6. Historique : Trace de l'utilisation des points
            if ($pointsUsed > 0) {
                \App\Models\LoyaltyTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => 'redeem',
                    'points' => -$pointsUsed,
                    'description' => "Réduction de {$discount_fcfa} FCFA sur la commande {$order->order_number}",
                ]);
            }

            // 7. Historique : Trace du gain du point
            \App\Models\LoyaltyTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'type' => 'earn',
                'points' => $pointsEarned,
                'description' => "Point de fidélité gagné pour la commande {$order->order_number}",
            ]);
          return $order;
        });

        Session::forget('cart');

        // Si l'étudiant a choisi OM / MoMo, on lance CinetPay
        if ($request->payment_method === 'mobile_money') {
            return PaiementController::initierCinetPay($order, Auth::user());
        }

        // Sinon (paiement en espèces / cash), on enregistre un paiement cash en attente
        \App\Models\Payment::create([
            'order_id'    => $order->id,
            'provider'    => 'local',
            'method'      => 'cash',
            'status'      => 'pending',
            'amount_fcfa' => $order->total_fcfa,
        ]);

        return redirect()->route('etudiant.menu.index')
            ->with('success', 'Votre commande a été envoyée en cuisine avec succès !');
        
    }
}