<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoyaltyController extends Controller
{
    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'points' => ['required', 'integer', 'min:1', 'max:1000000'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $studentRoleId = Role::whereIn('code', ['student', 'etudiant'])->value('id');
        $student = User::where('email', $validated['email'])
            ->where('role_id', $studentRoleId)
            ->firstOrFail();
        $expiryMonths = (int) AppSetting::where('setting_key', 'loyalty_points_expiry_months')->value('setting_value');

        DB::transaction(function () use ($student, $validated, $expiryMonths) {
            $account = LoyaltyAccount::firstOrCreate(
                ['user_id' => $student->id],
                ['balance_points' => 0]
            );
            $account = LoyaltyAccount::where('user_id', $student->id)->lockForUpdate()->firstOrFail();
            $account->increment('balance_points', $validated['points']);

            LoyaltyTransaction::create([
                'user_id' => $student->id,
                'type' => 'adjust',
                'points' => $validated['points'],
                'description' => $validated['description'],
                'expires_at' => $expiryMonths > 0 ? now()->addMonths($expiryMonths) : null,
            ]);
        });

        return redirect()->route('admin.stats')->with('success', 'Les points ont été crédités et enregistrés dans l’historique.');
    }
}
