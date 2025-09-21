<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\TestController;

// Admin test routes (no auth required)
Route::get('/admin/test', [TestController::class, 'test']);
Route::get('/admin/test-view', [TestController::class, 'testView']);

// Admin authentication routes
Route::get('/admin/login', [SuperAdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [SuperAdminController::class, 'login']);
Route::post('/admin/logout', [SuperAdminController::class, 'logout'])->name('admin.logout');

// Admin protected routes
Route::middleware(['web', 'super-admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [SuperAdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('admin.dashboard');

    // Tenants Management
    Route::get('/admin/tenants', [SuperAdminController::class, 'tenants'])->name('admin.tenants.index');
    Route::get('/admin/tenants/create', [SuperAdminController::class, 'createTenant'])->name('admin.tenants.create');
    Route::post('/admin/tenants', [SuperAdminController::class, 'storeTenant'])->name('admin.tenants.store');
    Route::get('/admin/tenants/{tenant}', [SuperAdminController::class, 'showTenant'])->name('admin.tenants.show');
    Route::get('/admin/tenants/{tenant}/edit', [SuperAdminController::class, 'editTenant'])->name('admin.tenants.edit');
    Route::put('/admin/tenants/{tenant}', [SuperAdminController::class, 'updateTenant'])->name('admin.tenants.update');
    Route::delete('/admin/tenants/{tenant}', [SuperAdminController::class, 'destroyTenant'])->name('admin.tenants.destroy');
});
