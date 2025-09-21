<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're in admin routes
        if ($request->is('admin*')) {
            // Get locale from session or default to Spanish
            $locale = session('admin_locale', 'es');
            
            // Validate locale
            $supportedLocales = ['es', 'en', 'nl'];
            if (!in_array($locale, $supportedLocales)) {
                $locale = 'es'; // Default to Spanish
            }
            
            // Set the application locale
            app()->setLocale($locale);
            
            // Also set the locale in the session if it wasn't set
            if (!session()->has('admin_locale')) {
                session(['admin_locale' => $locale]);
            }
        }

        return $next($request);
    }
}