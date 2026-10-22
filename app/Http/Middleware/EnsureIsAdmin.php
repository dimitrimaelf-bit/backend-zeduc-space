<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    // Traite une requête entrante et vérifie que l'utilisateur est administrateur (role_id = 4).
    
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérifie si l'utilisateur est connecté
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // 2. Vérifie si role_id vaut 4 (Administrateur)
        if ((int) $request->user()->role_id !== 4) {
            abort(403, 'Accès refusé : espace strictement réservé à l\'administrateur.');
        }

        return $next($request);
    }
}