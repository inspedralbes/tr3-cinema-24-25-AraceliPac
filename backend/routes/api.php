<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;

// Ruta que requiere autenticación con Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('users', UserController::class); //temporal nomes fins posar el middleware
//rutes
Route::apiResource('genres', GenreController::class);
Route::apiResource('directors', DirectorController::class);
Route::apiResource('movies', MovieController::class);
