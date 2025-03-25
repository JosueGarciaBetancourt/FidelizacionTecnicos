<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.api' => \App\Http\Middleware\VerifyApiKey::class,
            'BlockDirectHttpMethodAccess' => \App\Http\Middleware\BlockDirectHttpMethodAccess::class,
          ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ThrottleRequestsException $e, $request) {
            return response()->json([
                'error' => 'Demasiados intentos de login. Por favor intenta después.',
            ], 429);
        });
    })
    ->create();
