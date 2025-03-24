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
        Route::get('/', [AdminController::class, 'index'])->name('admin.home');
        Route::get('/home', [AdminController::class, 'index'])->name('admin.home');

        // Películas
        Route::get('/movies', [AdminController::class, 'movies'])->name('admin.movies.index');
        Route::get('/movies/create', [AdminController::class, 'createMovie'])->name('admin.movies.create');
        Route::post('/movies', [AdminController::class, 'storeMovie'])->name('admin.movies.store');
        Route::get('/movies/{id}', [AdminController::class, 'showMovie'])->name('admin.movies.show');
        Route::get('/movies/{id}/edit', [AdminController::class, 'editMovie'])->name('admin.movies.edit');
        Route::put('/movies/{id}', [AdminController::class, 'updateMovie'])->name('admin.movies.update');
        Route::delete('/movies/{id}', [AdminController::class, 'destroyMovie'])->name('admin.movies.destroy'); // Nueva ruta para eliminar

        // Proyecciones
        Route::get('/screenings', [AdminController::class, 'screenings'])->name('admin.screenings');
        Route::get('/screenings/create', [AdminController::class, 'createScreening'])->name('admin.screenings.create');
        Route::post('/screenings', [AdminController::class, 'storeScreening'])->name('admin.screenings.store');
        Route::get('/screenings/{id}', [AdminController::class, 'showScreening'])->name('admin.screenings.show');
        Route::get('/screenings/{id}/edit', [AdminController::class, 'editScreening'])->name('admin.screenings.edit');
        Route::put('/screenings/{id}', [AdminController::class, 'updateScreening'])->name('admin.screenings.update');
        Route::delete('/screenings/{id}', [AdminController::class, 'destroyScreening'])->name('admin.screenings.destroy'); // Nueva ruta para eliminar
        

        // Entradas
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
        Route::get('/tickets/create', [AdminController::class, 'createTicket'])->name('admin.tickets.create');

        // Usuarios y Configuración
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    });
