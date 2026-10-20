<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\CookieConsent;
use Illuminate\Http\Request;

class CookiesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['consented' => ['required', 'boolean']]);

        CookieConsent::create([
            'user_id'        => $request->user()?->id,
            'session_id'     => $request->user() ? null : $request->session()->getId(),
            'consented'      => $request->boolean('consented'),
            'policy_version' => '1.0',
        ]);

        $request->session()->put('cookie_consent_answered', true);

        return back();
    }
}