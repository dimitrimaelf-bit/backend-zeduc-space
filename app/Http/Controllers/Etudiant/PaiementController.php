<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Crée et lance la transaction CinetPay pour une commande existante
     */
    public static function initierCinetPay(Order $order, $user)
    {
        $transactionId = 'ZEDUC-' . $order->order_number . '-' . time();

        // 1. Si le total est 0 F (ex: payé entièrement avec les points), pas besoin de CinetPay
        if ($order->total_fcfa <= 0) {
            Payment::create([
                'order_id'                => $order->id,
                'provider'                => 'loyalty',
                'provider_transaction_id' => $transactionId,
                'method'                  => 'test',
                'status'                  => 'paid',
                'amount_fcfa'             => 0,
                'paid_at'                 => now(),
            ]);
            $order->update(['status' => 'confirmed']);
            return redirect()->route('etudiant.reclamations.index')
                ->with('success', 'Commande validée gratuitement grâce à vos points !');
        }

        // 2. Enregistrement du paiement en statut "pending" dans ta table `payments`
        $payment = Payment::create([
            'order_id'                => $order->id,
            'provider'                => 'cinetpay',
            'provider_transaction_id' => $transactionId,
            'method'                  => 'mobile_money',
            'status'                  => 'pending',
            'amount_fcfa'             => $order->total_fcfa,
        ]);

        // 3. Préparation des données pour CinetPay (Adapté à ton modèle User : first_name / last_name)
        $payload = [
            'apikey'                => config('services.cinetpay.apikey'),
            'site_id'               => config('services.cinetpay.site_id'),
            'transaction_id'        => $transactionId,
            'amount'                => (int) $order->total_fcfa, // Doit être un multiple de 5 (Min: 100 XAF)
            'currency'              => 'XAF',
            'description'           => 'Commande ' . $order->order_number . ' - ZeDuc@Space',
            'customer_name'         => $user->first_name,
            'customer_surname'      => $user->last_name,
            'customer_email'        => $user->email,
            'customer_phone_number' => $user->phone ?? '0707070707',
            'customer_address'      => $order->delivery_address ?? 'Douala',
            'customer_city'         => 'Douala',
            'customer_country'      => 'CM',
            'customer_state'        => 'CM',
            'customer_zip_code'     => '00000',
            'notify_url'            => route('cinetpay.notify'),
            'return_url'            => route('cinetpay.return', ['transaction_id' => $transactionId]),
            'channels'              => 'MOBILE_MONEY',
            'lang'                  => 'FR',
        ];

        // 4. Appel à l'API CinetPay (withoutVerifying évite le blocage SSL sous Windows/XAMPP)
        $response = Http::withoutVerifying()
            ->acceptJson()
            ->post('https://api-checkout.cinetpay.com/v2/payment', $payload);

        $result = $response->json();

        // 5. Si CinetPay renvoie bien le lien de paiement, on redirige l'étudiant dessus
        if (isset($result['code']) && $result['code'] == '201' && isset($result['data']['payment_url'])) {
            return redirect()->away($result['data']['payment_url']);
        }

        // En cas d'erreur de clés ou de connexion API
        Log::error('Erreur CinetPay Init : ', $result ?? []);
        return redirect()->route('etudiant.reclamations.index')
            ->withErrors(['error' => 'Commande enregistrée, mais la redirection vers CinetPay a échoué.']);
    }

    /**
     * Retour de l'étudiant sur le site après le paiement (en local, notify_url ne peut pas joindre localhost)
     */
    public function retourPaiement(Request $request)
    {
        $transactionId = $request->input('transaction_id') ?? $request->input('cpm_trans_id');

        if (!$transactionId) {
            return redirect()->route('etudiant.reclamations.index')
                ->withErrors(['error' => 'Aucune référence de transaction trouvée.']);
        }

        $payment = Payment::where('provider_transaction_id', $transactionId)->first();

        if ($payment) {
            // Vérification directe auprès de CinetPay
            $check = Http::withoutVerifying()->post('https://api-checkout.cinetpay.com/v2/payment/check', [
                'apikey'         => config('services.cinetpay.apikey'),
                'site_id'        => config('services.cinetpay.site_id'),
                'transaction_id' => $transactionId,
            ])->json();

            if (isset($check['code']) && $check['code'] === '00') {
                // Paiement réussi
                $payment->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
                $payment->order?->update(['status' => 'confirmed']);

                return redirect()->route('etudiant.reclamations.index')
                    ->with('success', 'Paiement Mobile Money accepté ! Votre commande est confirmée en cuisine.');
            } else {
                // Paiement échoué ou annulé
                $payment->update(['status' => 'failed']);
                return redirect()->route('etudiant.reclamations.index')
                    ->withErrors(['error' => 'Le paiement Mobile Money a échoué ou a été annulé.']);
            }
        }

        return redirect()->route('etudiant.reclamations.index');
    }

    /**
     * Webhook appelé en arrière-plan par les serveurs de CinetPay (en production)
     */
    public function notify(Request $request)
    {
        Log::info('Webhook CinetPay reçu : ', $request->all());
        $transactionId = $request->input('cpm_trans_id');

        if ($transactionId) {
            $payment = Payment::where('provider_transaction_id', $transactionId)->first();
            if ($payment && $payment->status !== 'paid') {
                $check = Http::withoutVerifying()->post('https://api-checkout.cinetpay.com/v2/payment/check', [
                    'apikey'         => config('services.cinetpay.apikey'),
                    'site_id'        => config('services.cinetpay.site_id'),
                    'transaction_id' => $transactionId,
                ])->json();

                if (isset($check['code']) && $check['code'] === '00') {
                    $payment->update(['status' => 'paid', 'paid_at' => now()]);
                    $payment->order?->update(['status' => 'confirmed']);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}