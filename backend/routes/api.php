<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;

// Ruta que requiere autenticación con Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/users', [UserController::class, 'index']);
Route::apiResource('genres', GenreController::class);

