<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Charger les routes admin
            $adminRoutes = __DIR__.'/../routes/admin.php';
            if (file_exists($adminRoutes)) {
                require $adminRoutes;
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Sanctum middleware pour les API
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->api(append: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // Rate limiting pour les API
        $middleware->api(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        ]);

        // Middleware web
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Alias de middleware
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'role_permission' => \App\Http\Middleware\RolePermissionMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Redirection personnalisée pour les erreurs d'auth
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié.'
                ], 401);
            }
            return '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
