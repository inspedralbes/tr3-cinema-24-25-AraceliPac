<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Ruta d'inici

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')
    ->middleware(['auth']) // Autenticació web estàndard
    ->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/home', [AdminController::class, 'index']);
        Route::get('/movies', [AdminController::class, 'movies']);
        Route::get('/screenings', [AdminController::class, 'screenings']);
        Route::get('/tickets', [AdminController::class, 'tickets']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/settings', [AdminController::class, 'settings']);

        // Rutes per a la creació
        Route::get('/movies/create', [AdminController::class, 'createMovie']);
        Route::get('/screenings/create', [AdminController::class, 'createScreening']);
        Route::get('/tickets/create', [AdminController::class, 'createTicket']);
    });
