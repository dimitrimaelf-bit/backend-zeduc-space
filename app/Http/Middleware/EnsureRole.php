<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        $hasRequiredRole = $user !== null && collect($roles)->contains(
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