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
            // Tenant routes with automatic domain detection
            Route::group([], function () {
                // Check if this is a tenant subdomain
                $host = request()->getHost();
                if (str_ends_with($host, '.book.aimadarek.com')) {
                    $tenantId = str_replace('.book.aimadarek.com', '', $host);
                    $tenant = \App\Models\Tenant::where('id', $tenantId)->first();
                    
                    if ($tenant) {
                        tenancy()->initialize($tenant);
                        require base_path('routes/tenant.php');
                    }
                }
            });
        },
    )
        ->withMiddleware(function (Middleware $middleware) {
            // Register custom middleware
            $middleware->alias([
                'SetLocaleFromRequest' => \App\Http\Middleware\SetLocaleFromRequest::class,
                'tenant-panel' => \App\Http\Middleware\TenantPanelAuth::class,
                'super-admin' => \App\Http\Middleware\SuperAdminAuth::class,
                'tenancy' => \App\Http\Middleware\InitializeTenancyByDomain::class,
            ]);
            
            // Add locale and tenancy middleware to all web routes
            $middleware->web(prepend: [
                \App\Http\Middleware\SetLocaleFromRequest::class,
                \App\Http\Middleware\InitializeTenancyByDomain::class,
            ]);
            
            // Add locale middleware to API routes
            $middleware->api(prepend: [
                \App\Http\Middleware\SetLocaleFromRequest::class,
            ]);
        })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
