<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and are assigned
| the "tenant" middleware group. These routes are tenant-specific.
|
*/

// Test route to verify tenant is working
Route::get('/test', function () {
    if (tenancy()->initialized) {
        return response()->json([
            'tenant_id' => tenancy()->tenant->id,
            'brand_name' => tenancy()->tenant->brand_name,
            'active' => tenancy()->tenant->active,
            'message' => 'Tenant initialized successfully'
        ]);
    } else {
        return response()->json([
            'message' => 'Tenant not initialized'
        ]);
    }
});

// Redirect tenant root to their admin panel
Route::get('/', function () {
    if (tenancy()->initialized) {
        return redirect()->route('panel.dashboard');
    } else {
        // Fallback to widget if tenant not initialized
        return view('tenant.widget');
    }
})->name('tenant.dashboard');

// Widget route for public booking
Route::get('/widget', function () {
    return view('tenant.widget');
})->name('tenant.widget');

// Panel routes
Route::prefix('panel')->group(base_path('routes/panel.php'));

Route::get('/api/v1/availability', [App\Http\Controllers\Api\V1\AvailabilityController::class, 'index'])
    ->name('tenant.api.availability');

Route::post('/api/v1/book', [App\Http\Controllers\Api\V1\BookingController::class, 'store'])
    ->name('tenant.api.book');

Route::delete('/api/v1/book/{booking}', [App\Http\Controllers\Api\V1\BookingController::class, 'cancel'])
    ->name('tenant.api.cancel');