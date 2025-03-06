<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ActorController;

// Ruta que requiere autenticación con Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('users', UserController::class); //temporal nomes fins posar el middleware
//rutes
Route::apiResource('genres', GenreController::class);
Route::apiResource('directors', DirectorController::class);
Route::apiResource('movies', MovieController::class);
Route::apiResource('screenings', ScreeningController::class);
Route::apiResource('actors', ActorController::class);
// Rutes per a les butaques d'una sessió
Route::prefix('screenings/{screening}')->group(function () {
    Route::get('/seats', [SeatController::class, 'index']); // Llistar butaques
    Route::post('/seats', [SeatController::class, 'store']); // Crear butaca
});

// Rutes per a una butaca específica
Route::apiResource('seats', SeatController::class)->except(['index', 'store']);
Route::apiResource('tickets', TicketController::class);

