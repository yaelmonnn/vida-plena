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
        $middleware->appendToGroup('web', \App\Http\Middleware\SesionInactividad::class);
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\EnsureAdminAuth::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        // ── Excluir el webhook de Stripe de la verificación CSRF ──

        $middleware->redirectGuestsTo(function ($request) {
            return route('login.usuario');
        });

        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
