<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ArticleController;
use App\Http\Controllers\API\V1\CategoryController;

// API v1 routes
Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        
        // Article routes
        Route::apiResource('articles', ArticleController::class);
        
        // Category routes
        Route::apiResource('categories', CategoryController::class);
    });
});