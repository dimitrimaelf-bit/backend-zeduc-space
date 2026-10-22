<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\CookieConsent;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class Cookies extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['consented' => ['required', 'boolean']]);
        $policyVersion = AppSetting::where('setting_key', 'cookie_policy_version')->value('setting_value') ?? '1.0';

        CookieConsent::create([
            'user_id'        => $request->user()?->id,                                 // visiteur : null
            'session_id'     => $request->user() ? null : $request->session()->getId(),
            'consented'      => $request->boolean('consented'),
            'policy_version' => $policyVersion,
        ]);

        $request->session()->put('cookie_consent_version', $policyVersion);

        return back();
    }
}