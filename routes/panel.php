<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\DashboardController;

/*
|--------------------------------------------------------------------------
| Panel Routes
|--------------------------------------------------------------------------
|
| Here is where you can register panel routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "panel" middleware group.
|
*/

// Authentication routes (no auth middleware)
Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('panel.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('panel.logout');
});

// Protected panel routes
Route::middleware(['web', 'tenant-panel'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('panel.dashboard');
    Route::get('/calendar', [DashboardController::class, 'calendar'])->name('panel.calendar');
    
    // Bookings
    Route::resource('bookings', \App\Http\Controllers\Panel\BookingController::class)->names([
        'index' => 'panel.bookings.index',
        'create' => 'panel.bookings.create',
        'store' => 'panel.bookings.store',
        'show' => 'panel.bookings.show',
        'edit' => 'panel.bookings.edit',
        'update' => 'panel.bookings.update',
        'destroy' => 'panel.bookings.destroy',
    ]);
    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\Panel\BookingController::class, 'cancel'])->name('panel.bookings.cancel');
    Route::post('bookings/{booking}/no-show', [\App\Http\Controllers\Panel\BookingController::class, 'noShow'])->name('panel.bookings.no-show');
    Route::get('bookings/available-slots', [\App\Http\Controllers\Panel\BookingController::class, 'getAvailableSlots'])->name('panel.bookings.available-slots');
    
    // Services
    Route::resource('services', \App\Http\Controllers\Panel\ServiceController::class)->names([
        'index' => 'panel.services.index',
        'create' => 'panel.services.create',
        'store' => 'panel.services.store',
        'show' => 'panel.services.show',
        'edit' => 'panel.services.edit',
        'update' => 'panel.services.update',
        'destroy' => 'panel.services.destroy',
    ]);
    Route::patch('services/{service}/toggle', [\App\Http\Controllers\Panel\ServiceController::class, 'toggle'])->name('panel.services.toggle');
    
    // Resources
    Route::resource('resources', \App\Http\Controllers\Panel\ResourceController::class)->names([
        'index' => 'panel.resources.index',
        'create' => 'panel.resources.create',
        'store' => 'panel.resources.store',
        'show' => 'panel.resources.show',
        'edit' => 'panel.resources.edit',
        'update' => 'panel.resources.update',
        'destroy' => 'panel.resources.destroy',
    ]);
    Route::patch('resources/{resource}/toggle', [\App\Http\Controllers\Panel\ResourceController::class, 'toggle'])->name('panel.resources.toggle');
    
    // Availability
    Route::get('availability', function() { return view('panel.availability.index'); })->name('panel.availability.index');
    
    // Customers
    Route::resource('customers', \App\Http\Controllers\Panel\CustomerController::class)->names([
        'index' => 'panel.customers.index',
        'create' => 'panel.customers.create',
        'store' => 'panel.customers.store',
        'show' => 'panel.customers.show',
        'edit' => 'panel.customers.edit',
        'update' => 'panel.customers.update',
        'destroy' => 'panel.customers.destroy',
    ]);
    
    // Webhooks
    Route::get('webhooks', function() { return view('panel.webhooks.index'); })->name('panel.webhooks.index');
    
    // API Tokens
    Route::get('tokens', function() { return view('panel.tokens.index'); })->name('panel.tokens.index');
    
    // Settings
    Route::get('settings', function() { return view('panel.settings.index'); })->name('panel.settings.index');
    Route::get('settings/profile', function() { return view('panel.settings.profile'); })->name('panel.settings.profile');
    Route::get('settings/locale/{locale}', function($locale) { 
        app()->setLocale($locale);
        return redirect()->back();
    })->name('panel.settings.locale');
    
    // Onboarding
    Route::get('onboarding', function() { return view('panel.onboarding.index'); })->name('panel.onboarding.index');
});
