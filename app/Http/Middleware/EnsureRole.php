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

        if ($user === null) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérification que le compte est actif (ajout de la branche etudiant)
        if (isset($user->is_active) && ! $user->is_active) {
            abort(403, 'Compte désactivé.');
        }

        $hasRequiredRole = collect($roles)->contains(
            fn (string $role) => is_numeric($role)
                ? (int) $user->role_id === (int) $role
                : $user->role?->code === $role
        );

        if (! $hasRequiredRole) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}