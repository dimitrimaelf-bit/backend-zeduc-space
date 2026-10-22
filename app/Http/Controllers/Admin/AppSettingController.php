<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function index()
    {
        $settings = AppSetting::with('updatedBy:id,first_name,last_name')
            ->get()
            ->keyBy('setting_key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, string $key)
    {
        $rules = match ($key) {
            'restaurant_open', 'restaurant_close' => ['required', 'date_format:H:i'],
            'loyalty_fcfa_per_point',
            'loyalty_points_per_1000_fcfa_discount',
            'loyalty_discount_fcfa',
            'loyalty_points_expiry_months' => ['required', 'integer', 'min:1', 'max:1000000'],
            'privacy_policy_url', 'terms_of_service_url' => ['nullable', 'url', 'max:500'],
            'cookie_policy_version' => ['required', 'string', 'max:30'],
            default => abort(404),
        };

        $validated = $request->validate(['setting_value' => $rules]);

        AppSetting::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => (string) $validated['setting_value'],
                'updated_by' => $request->user()->id,
            ]
        );

        return redirect()->route('admin.settings.index')
            ->with('success', "Le paramètre '{$key}' a été mis à jour.");
    }
}
