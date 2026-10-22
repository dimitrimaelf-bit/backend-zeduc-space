<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
        ->withMiddleware(function (Middleware $middleware): void {
        // Déclare l'alias "role" utilisé dans routes/web.php : ->middleware('role:student')
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);
        // Autorise CinetPay à renvoyer les données en POST sans erreur 419 (CSRF)
        $middleware->validateCsrfTokens(except: [
            'paiement/notify',
            'paiement/retour',
        ]);
    })