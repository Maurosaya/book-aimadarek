<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('tenant')
                ->prefix('{tenant}')
                ->group(base_path('routes/tenant.php'));
        },
    )
        ->withMiddleware(function (Middleware $middleware) {
            // Register custom middleware
            $middleware->alias([
                'SetLocaleFromRequest' => \App\Http\Middleware\SetLocaleFromRequest::class,
            ]);
            
            // Add locale middleware to all web routes
            $middleware->web(prepend: [
                \App\Http\Middleware\SetLocaleFromRequest::class,
            ]);
            
            // Add locale middleware to API routes
            $middleware->api(prepend: [
                \App\Http\Middleware\SetLocaleFromRequest::class,
            ]);
        })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
