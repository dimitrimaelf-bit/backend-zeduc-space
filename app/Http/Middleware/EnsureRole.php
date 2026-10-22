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

        $hasRequiredRole = collect($roles)->contains(
            fn (string $role) => is_numeric($role)
                ? (int) $user->role_id === (int) $role
                : $user->role?->code === $role
        );

        abort_unless($hasRequiredRole, 403, "Vous n'avez pas accès à cette page.");

        return $next($request);
    }
}