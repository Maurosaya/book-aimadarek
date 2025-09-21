<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyByDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the host from the request
        $host = $request->getHost();
        
        // Check if this is a tenant subdomain
        if (str_ends_with($host, '.book.aimadarek.com')) {
            // Extract tenant ID from subdomain
            $tenantId = str_replace('.book.aimadarek.com', '', $host);
            
            // Find tenant by ID
            $tenant = \App\Models\Tenant::where('id', $tenantId)->first();
            
            if ($tenant) {
                // Initialize tenancy for this tenant
                tenancy()->initialize($tenant);
                return $next($request);
            }
        }
        
        // If not a tenant subdomain or tenant not found, continue without tenancy
        return $next($request);
    }
}
