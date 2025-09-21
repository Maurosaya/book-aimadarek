<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AvailabilityController;
use App\Http\Controllers\Api\V1\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API v1 routes with multitenancy and authentication
Route::prefix('v1')->middleware([
    'api',
    'InitializeTenancyByDomain',
    'SetLocaleFromRequest',
    'auth:sanctum',
    'throttle:60,1'
])->group(function () {
    
    // Availability endpoints
    Route::get('/availability', [AvailabilityController::class, 'index'])
        ->name('api.v1.availability');
    
    // Booking endpoints
    Route::post('/book', [BookingController::class, 'store'])
        ->name('api.v1.book');
    
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('api.v1.bookings.cancel');
    
    Route::get('/bookings/{id}', [BookingController::class, 'show'])
        ->name('api.v1.bookings.show');
});

// Health check endpoint (no authentication required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
    ]);
})->name('api.health');
