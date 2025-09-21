<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Panel Authentication Controller
 * 
 * Handles login/logout for tenant panel users
 * Includes tenant-specific authentication logic
 */
class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return redirect()->route('panel.dashboard');
        }

        return view('panel.auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get current tenant
        if (!tenancy()->initialized) {
            throw ValidationException::withMessages([
                'email' => ['Tenant not found'],
            ]);
        }

        $currentTenant = tenancy()->tenant;
        
        if (!$currentTenant) {
            throw ValidationException::withMessages([
                'email' => ['Tenant not found'],
            ]);
        }

        // Find user in current tenant
        $user = \App\Models\User::where('email', $request->email)
            ->where('tenant_id', $currentTenant->id)
            ->where('active', true)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        
        // Update last login
        $user->updateLastLogin();

        // Redirect to intended page or dashboard
        return redirect()->intended(route('panel.dashboard'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('panel.login');
    }
}