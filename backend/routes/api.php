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
use App\Http\Controllers\AuthController;

// Ruta que requiere autenticación con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::post('/login', [AuthController::class, 'login']);  
Route::post('/register', [AuthController::class, 'register']);
Route::apiResource('users', UserController::class); //temporal nomes fins posar el middleware
//rutes
Route::apiResource('genres', GenreController::class);
Route::apiResource('directors', DirectorController::class);
// Route::apiResource('movies', MovieController::class);
Route::apiResource('screenings', ScreeningController::class);
Route::apiResource('actors', ActorController::class);
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{id}', [MovieController::class, 'show']);
Route::get('/movies/{id}/actors', [MovieController::class, 'getActors']);
Route::post('/movies', [MovieController::class, 'store']);
Route::put('/movies/{id}', [MovieController::class, 'update']);
Route::delete('/movies/{id}', [MovieController::class, 'destroy']);
// Rutes per a les butaques d'una sessió
Route::prefix('screenings/{screening}')->group(function () {
    Route::get('/seats', [SeatController::class, 'index']); // Llistar butaques
    Route::post('/seats', [SeatController::class, 'store']); // Crear butaca
});
// routes/api.php

// Rutes per a una butaca específica
Route::apiResource('seats', SeatController::class)->except(['index', 'store']);
Route::apiResource('tickets', TicketController::class);

