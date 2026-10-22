<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order.user'])
            ->where('method', 'cash')
            ->where('status', 'pending')
            ->latest('created_at')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function confirmCash(Request $request, Payment $payment)
    {
        abort_unless($payment->method === 'cash', 404);

        DB::transaction(function () use ($request, $payment) {
            $lockedPayment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            if ($lockedPayment->status === 'paid') {
                return;
            }
            abort_unless($lockedPayment->status === 'pending', 409);

            $order = $lockedPayment->order()->lockForUpdate()->firstOrFail();
            $lockedPayment->update(['status' => 'paid', 'paid_at' => now()]);
            $order->update(['status' => 'confirmed']);

            $points = (int) $order->loyalty_points_earned;
            if ($points < 1 || LoyaltyTransaction::where('order_id', $order->id)->where('type', 'earn')->exists()) {
                return;
            }

            $account = LoyaltyAccount::firstOrCreate(
                ['user_id' => $order->user_id],
                ['balance_points' => 0]
            );
            $account = LoyaltyAccount::where('user_id', $account->user_id)->lockForUpdate()->firstOrFail();
            $account->increment('balance_points', $points);

            $expiryMonths = (int) AppSetting::where('setting_key', 'loyalty_points_expiry_months')->value('setting_value');
            $expiryMonths = $expiryMonths > 0 ? $expiryMonths : 12;

            LoyaltyTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'type' => 'earn',
                'points' => $points,
                'description' => 'Points gagnés après paiement de la commande '.$order->order_number,
                'expires_at' => now()->addMonths($expiryMonths),
            ]);
        });

        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement espèces confirmé; les points ont été crédités si la commande en ouvre droit.');
    }
}
