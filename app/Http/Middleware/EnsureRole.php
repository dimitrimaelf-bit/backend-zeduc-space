<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// "auth" vérifie QUI vous êtes ; "role" vérifie CE QUE vous avez le droit de faire
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if(! $user || ! $user->is_active, 403, 'Compte désactivé.');
        abort_unless(in_array($user->role?->code, $roles, true), 403, "Vous n'avez pas accès à cette page.");

        return $next($request);
    }
}