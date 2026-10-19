<?php

namespace App\Http\Controllers;

use App\Models\CookieConsent;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['consented' => ['required', 'boolean']]);

        CookieConsent::create([
            'user_id'        => $request->user()?->id,                                 // visiteur : null
            'session_id'     => $request->user() ? null : $request->session()->getId(),
            'consented'      => $request->boolean('consented'),
            'policy_version' => '1.0',            // à changer si votre politique change
        ]);

        $request->session()->put('cookie_consent_answered', true); // masque le bandeau

        return back();
    }
}