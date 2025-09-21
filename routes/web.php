<?php

use App\Http\Controllers\Landing\HomeController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Demo availability API endpoint
Route::get('/demo/access', [LandingController::class, 'getDemoAccess'])->name('demo.access');
Route::get('/demo/availability', [LandingController::class, 'getAvailability'])->name('demo.availability');

// Tenant routes (for demo purposes)
Route::prefix('tenant/{tenant}')->group(function () {
    // Tenant panel route
    Route::get('/panel', function ($tenant) {
        return redirect("https://{$tenant}.book.aimadarek.com/panel");
    })->name('tenant.panel');
    
    // Tenant widget route  
    Route::get('/', function ($tenant) {
        return redirect("https://{$tenant}.book.aimadarek.com");
    })->name('tenant.widget');
});

// Simple tenant routes - without tenancy middleware
Route::get('/tenant/{tenant}', function ($tenant) {
    try {
        $tenantModel = \App\Models\Tenant::where('id', $tenant)->first();
        if ($tenantModel) {
            // Initialize tenancy manually
            tenancy()->initialize($tenantModel);
            return view('tenant.widget');
        }
        return response('Tenant not found', 404);
    } catch (\Exception $e) {
        return response('Error: ' . $e->getMessage(), 500);
    }
});

Route::get('/tenant/{tenant}/panel', function ($tenant) {
    try {
        $tenantModel = \App\Models\Tenant::where('id', $tenant)->first();
        if ($tenantModel) {
            // Initialize tenancy manually
            tenancy()->initialize($tenantModel);
            return view('tenant.panel');
        }
        return response('Tenant not found', 404);
    } catch (\Exception $e) {
        return response('Error: ' . $e->getMessage(), 500);
    }
});
