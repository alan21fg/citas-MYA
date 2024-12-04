<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middlewares de grupo
        $middleware->group('api', [
            // Otros middlewares específicos del grupo API
            \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
        ]);

        $middleware->group('web', []);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
