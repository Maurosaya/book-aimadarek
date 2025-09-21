<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Tenancy;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromRequest
{
    /**
     * Handle an incoming request.
     * 
     * Sets the application locale based on:
     * 1. Query parameter ?locale=xx
     * 2. Accept-Language header
     * 3. Tenant default locale
     * 4. Global default locale (en)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['es', 'en', 'nl'];
        $locale = null;

        // 1. Check query parameter first
        if ($request->has('locale') && in_array($request->get('locale'), $supportedLocales)) {
            $locale = $request->get('locale');
        }
        // 2. Check Accept-Language header
        elseif ($request->hasHeader('Accept-Language')) {
            $acceptLanguage = $request->header('Accept-Language');
            $preferredLocale = $this->parseAcceptLanguage($acceptLanguage, $supportedLocales);
            if ($preferredLocale) {
                $locale = $preferredLocale;
            }
        }
        // 3. Check tenant default locale
        elseif (tenancy()->initialized) {
            $tenant = tenancy()->tenant;
            if ($tenant && isset($tenant->default_locale)) {
                $locale = $tenant->default_locale;
            }
        }

        // 4. Fallback to global default
        if (!$locale) {
            $locale = config('app.locale', 'en');
        }

        // Set the locale
        App::setLocale($locale);

        return $next($request);
    }

    /**
     * Parse Accept-Language header and return the first supported locale
     */
    private function parseAcceptLanguage(string $acceptLanguage, array $supportedLocales): ?string
    {
        $languages = [];
        
        // Parse Accept-Language header (e.g., "en-US,en;q=0.9,es;q=0.8")
        foreach (explode(',', $acceptLanguage) as $lang) {
            $lang = trim(explode(';', $lang)[0]);
            $lang = explode('-', $lang)[0]; // Get only the language part (en from en-US)
            
            if (in_array($lang, $supportedLocales)) {
                return $lang;
            }
        }

        return null;
    }
}
