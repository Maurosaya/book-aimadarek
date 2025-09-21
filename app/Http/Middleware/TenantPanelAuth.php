<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tenant Panel Authentication Middleware
 * 
 * Ensures user is authenticated and belongs to the current tenant
 * Redirects to login if not authenticated or unauthorized
 */
class TenantPanelAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('panel.login');
        }

        $user = Auth::user();

        // Check if tenancy is initialized
        if (!tenancy()->initialized) {
            abort(404, 'Tenant not found');
        }

        $currentTenant = tenancy()->tenant;
        
        if (!$currentTenant) {
            abort(404, 'Tenant not found');
        }

        // Check if user belongs to current tenant
        if ($user->tenant_id !== $currentTenant->id) {
            Auth::logout();
            return redirect()->route('panel.login')->with('error', 'Unauthorized access to tenant panel');
        }

        // Check if user is active
        if (!$user->active) {
            Auth::logout();
            return redirect()->route('panel.login')->with('error', 'Account is deactivated');
        }

        // Update last login timestamp
        $user->updateLastLogin();

        // Share user data with views
        view()->share('currentUser', $user);
        view()->share('currentTenant', $currentTenant);

        return $next($request);
    }
}