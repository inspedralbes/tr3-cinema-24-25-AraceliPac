<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\ScreeningController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\ActorController;
use App\Http\Controllers\Admin\DirectorController;
use App\Http\Controllers\Admin\GenreController;

// Ruta d'inici
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/admin/login', [DashboardController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [DashboardController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')
    ->middleware(['auth']) // Autenticació web estàndard
    ->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('admin.home');
        Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');

        // Perfil d'usuari
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

        // Películas
        Route::get('/movies', [MovieController::class, 'index'])->name('admin.movies.index');
        Route::get('/movies/create', [MovieController::class, 'create'])->name('admin.movies.create');
        Route::post('/movies', [MovieController::class, 'store'])->name('admin.movies.store');
        Route::get('/movies/{id}', [MovieController::class, 'show'])->name('admin.movies.show');
        Route::get('/movies/{id}/edit', [MovieController::class, 'edit'])->name('admin.movies.edit');
        Route::put('/movies/{id}', [MovieController::class, 'update'])->name('admin.movies.update');
        Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('admin.movies.destroy');

        // Proyecciones
        Route::get('/screenings', [ScreeningController::class, 'index'])->name('admin.screenings');
        Route::get('/screenings/create', [ScreeningController::class, 'create'])->name('admin.screenings.create');
        Route::post('/screenings', [ScreeningController::class, 'store'])->name('admin.screenings.store');
        Route::get('/screenings/{id}', [ScreeningController::class, 'show'])->name('admin.screenings.show');
        Route::get('/screenings/{id}/edit', [ScreeningController::class, 'edit'])->name('admin.screenings.edit');
        Route::put('/screenings/{id}', [ScreeningController::class, 'update'])->name('admin.screenings.update');
        Route::delete('/screenings/{id}', [ScreeningController::class, 'destroy'])->name('admin.screenings.destroy');

        // Tickets
        Route::get('/tickets', [TicketController::class, 'index'])->name('admin.tickets');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('admin.tickets.create'); // ✅ Esto está bien
        Route::post('/tickets', [TicketController::class, 'store'])->name('admin.tickets.store'); // ✅ Esto es para guardar el ticket
        Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('admin.tickets.show');
        Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('admin.tickets.edit');
        Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('admin.tickets.update');
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');

        // Usuarios
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Ventas
        Route::get('/sales', [SalesController::class, 'index'])->name('admin.sales');
        Route::get('/sales/daily', [SalesController::class, 'dailyReport'])->name('admin.sales.daily');
        Route::get('/sales/movie', [SalesController::class, 'movieReport'])->name('admin.sales.movie');
        Route::get('/sales/screening', [SalesController::class, 'screeningReport'])->name('admin.sales.screening');

        // Configuraciones
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');

        // Rutas para actores
        Route::get('/settings/actors', [ActorController::class, 'index'])->name('admin.settings.actors.index');
        Route::get('/settings/actors/create', [ActorController::class, 'create'])->name('admin.settings.actors.create');
        Route::post('/settings/actors', [ActorController::class, 'store'])->name('admin.settings.actors.store');
        Route::get('/settings/actors/{id}', [ActorController::class, 'show'])->name('admin.settings.actors.show');
        Route::get('/settings/actors/{id}/edit', [ActorController::class, 'edit'])->name('admin.settings.actors.edit');
        Route::put('/settings/actors/{id}', [ActorController::class, 'update'])->name('admin.settings.actors.update');
        Route::delete('/settings/actors/{id}', [ActorController::class, 'destroy'])->name('admin.settings.actors.destroy');

        // Rutas para directores
        Route::get('/settings/directors', [DirectorController::class, 'index'])->name('admin.settings.directors.index');
        Route::get('/settings/directors/create', [DirectorController::class, 'create'])->name('admin.settings.directors.create');
        Route::post('/settings/directors', [DirectorController::class, 'store'])->name('admin.settings.directors.store');
        Route::get('/settings/directors/{id}', [DirectorController::class, 'show'])->name('admin.settings.directors.show');
        Route::get('/settings/directors/{id}/edit', [DirectorController::class, 'edit'])->name('admin.settings.directors.edit');
        Route::put('/settings/directors/{id}', [DirectorController::class, 'update'])->name('admin.settings.directors.update');
        Route::delete('/settings/directors/{id}', [DirectorController::class, 'destroy'])->name('admin.settings.directors.destroy');

        // Rutas para géneros
        Route::get('/settings/genres', [GenreController::class, 'index'])->name('admin.settings.genres.index');
        Route::get('/settings/genres/create', [GenreController::class, 'create'])->name('admin.settings.genres.create');
        Route::post('/settings/genres', [GenreController::class, 'store'])->name('admin.settings.genres.store');
        Route::get('/settings/genres/{id}', [GenreController::class, 'show'])->name('admin.settings.genres.show');
        Route::get('/settings/genres/{id}/edit', [GenreController::class, 'edit'])->name('admin.settings.genres.edit');
        Route::put('/settings/genres/{id}', [GenreController::class, 'update'])->name('admin.settings.genres.update');
        Route::delete('/settings/genres/{id}', [GenreController::class, 'destroy'])->name('admin.settings.genres.destroy');
    });
