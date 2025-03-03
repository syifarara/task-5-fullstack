<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ArticleController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Article routes
    Route::resource('articles', ArticleController::class);
    
    // Category routes
    Route::resource('categories', CategoryController::class);
})->middleware('auth');