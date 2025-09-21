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

Route::get('/', function () {
    return view('tenant.widget');
})->name('tenant.widget');

Route::get('/panel', function () {
    return view('tenant.panel');
})->name('tenant.panel');

Route::get('/api/v1/availability', [App\Http\Controllers\Api\V1\AvailabilityController::class, 'index'])
    ->name('tenant.api.availability');

Route::post('/api/v1/book', [App\Http\Controllers\Api\V1\BookingController::class, 'store'])
    ->name('tenant.api.book');

Route::delete('/api/v1/book/{booking}', [App\Http\Controllers\Api\V1\BookingController::class, 'cancel'])
    ->name('tenant.api.cancel');
