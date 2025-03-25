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
        // Perfil d'usuari
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('admin.profile.password');
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


        // Rutas para tickets
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
        Route::get('/tickets/create', [AdminController::class, 'createTicket'])->name('admin.tickets.create');
        Route::post('/tickets', [AdminController::class, 'storeTicket'])->name('admin.tickets.store');
        Route::get('/tickets/{id}', [AdminController::class, 'showTicket'])->name('admin.tickets.show');
        Route::get('/tickets/{id}/edit', [AdminController::class, 'editTicket'])->name('admin.tickets.edit');
        Route::put('/tickets/{id}', [AdminController::class, 'updateTicket'])->name('admin.tickets.update');
        Route::delete('/tickets/{id}', [AdminController::class, 'destroyTicket'])->name('admin.tickets.destroy');

        // Rutas CRUD para usuarios
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        // Rutas para configuracions
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    });
