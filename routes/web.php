<?php

use App\Http\Controllers\Landing\HomeController;
use Illuminate\Support\Facades\Route;

// Landing page route
Route::get('/', [HomeController::class, 'index'])->name('landing');
