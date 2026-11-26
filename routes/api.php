<?php

use App\Http\Controllers\Api\AnnonceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProduitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::apiResource('annonces', AnnonceController::class)->only(['index', 'show']);
    Route::apiResource('produits', ProduitController::class)->only(['index', 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::apiResource('annonces', AnnonceController::class)->except(['index', 'show']);
    });
});