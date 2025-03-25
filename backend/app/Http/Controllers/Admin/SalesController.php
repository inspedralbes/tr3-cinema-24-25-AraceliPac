<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Movie;
use App\Models\Screening;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Verifica el acceso de administrador
     */
    protected function checkAdminAccess()
    {
        if (!Auth::check()) {
            return false;
        }

        if (Auth::user()->role_id != 1) {
            return false;
        }

        return true;
    }

    /**
     * Muestra la página principal de ventas con estadísticas
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Obtener estadísticas generales
        $totalSales = Ticket::sum('price');
        $totalTickets = Ticket::count();

        // Ventas de hoy
        $todaySales = Ticket::whereDate('created_at', Carbon::today())->sum('price');
        $todayTickets = Ticket::whereDate('created_at', Carbon::today())->count();

        // Ventas por película (top 5)
        $movieSales = Ticket::select('movies.id', 'movies.title', DB::raw('SUM(tickets.price) as total_sales'), DB::raw('COUNT(tickets.id) as tickets_count'))
            ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
            ->join('movies', 'screenings.movie_id', '=', 'movies.id')
            ->groupBy('movies.id', 'movies.title')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();

        // Ventas por proyección (próximas)
        $screeningSales = Ticket::select(
            'screenings.id',
            'movies.title',
            'screenings.screening_date',
            'screenings.screening_time',
            DB::raw('SUM(tickets.price) as total_sales'),
            DB::raw('COUNT(tickets.id) as tickets_count')
        )
            ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
            ->join('movies', 'screenings.movie_id', '=', 'movies.id')
            ->where('screenings.screening_date', '>=', Carbon::now()->format('Y-m-d'))
            ->groupBy('screenings.id', 'movies.title', 'screenings.screening_date', 'screenings.screening_time')
            ->orderBy('screenings.screening_date')
            ->orderBy('screenings.screening_time')
            ->limit(10)
            ->get();

        // Ventas por día (últimos 7 días)
        $dailySales = Ticket::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(price) as total_sales'),
            DB::raw('COUNT(id) as tickets_count')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.sales.index', compact(
            'totalSales',
            'totalTickets',
            'todaySales',
            'todayTickets',
            'movieSales',
            'screeningSales',
            'dailySales'
        ));
    }

    /**
     * Muestra estadísticas detalladas por día
     */
    public function dailyReport(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Fecha por defecto es hoy, pero puede ser cambiada por el usuario
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // Ventas del día
        $daySales = Ticket::whereDate('created_at', $date)->sum('price');
        $dayTickets = Ticket::whereDate('created_at', $date)->count();

        // Ventas por película en ese día
        $movieSales = Ticket::select(
            'movies.id',
            'movies.title',
            DB::raw('SUM(tickets.price) as total_sales'),
            DB::raw('COUNT(tickets.id) as tickets_count')
        )
            ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
            ->join('movies', 'screenings.movie_id', '=', 'movies.id')
            ->whereDate('tickets.created_at', $date)
            ->groupBy('movies.id', 'movies.title')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Ventas por proyección en ese día
        $screeningSales = Ticket::select(
            'screenings.id',
            'movies.title',
            'screenings.screening_date',
            'screenings.screening_time',
            DB::raw('SUM(tickets.price) as total_sales'),
            DB::raw('COUNT(tickets.id) as tickets_count')
        )
            ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
            ->join('movies', 'screenings.movie_id', '=', 'movies.id')
            ->whereDate('tickets.created_at', $date)
            ->groupBy('screenings.id', 'movies.title', 'screenings.screening_date', 'screenings.screening_time')
            ->orderBy('screenings.screening_time')
            ->get();

        return view('admin.sales.daily', compact(
            'date',
            'daySales',
            'dayTickets',
            'movieSales',
            'screeningSales'
        ));
    }


    /**
     * Muestra estadísticas detalladas por película
     */
    public function movieReport(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Lista de películas para el selector
        $movies = Movie::orderBy('title')->get();

        // Película seleccionada
        $movieId = $request->movie_id;
        $selectedMovie = null;
        $movieStats = null; // Inicializar como null
        $screeningSales = collect(); // Inicializar como colección vacía
        $dailySales = collect(); // Inicializar como colección vacía

        if ($movieId) {
            $selectedMovie = Movie::findOrFail($movieId);

            // Estadísticas generales de la película
            $movieStats = Ticket::select(
                DB::raw('SUM(tickets.price) as total_sales'),
                DB::raw('COUNT(tickets.id) as tickets_count')
            )
                ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
                ->where('screenings.movie_id', $movieId)
                ->first();

            // Ventas por proyección de esta película
            $screeningSales = Ticket::select(
                'screenings.id',
                'screenings.screening_date',
                'screenings.screening_time',
                DB::raw('SUM(tickets.price) as total_sales'),
                DB::raw('COUNT(tickets.id) as tickets_count')
            )
                ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
                ->where('screenings.movie_id', $movieId)
                ->groupBy('screenings.id', 'screenings.screening_date', 'screenings.screening_time')
                ->orderBy('screenings.screening_date')
                ->orderBy('screenings.screening_time')
                ->get();

            // Ventas por día para esta película
            $dailySales = Ticket::select(
                DB::raw('DATE(tickets.created_at) as date'),
                DB::raw('SUM(tickets.price) as total_sales'),
                DB::raw('COUNT(tickets.id) as tickets_count')
            )
                ->join('screenings', 'tickets.screening_id', '=', 'screenings.id')
                ->where('screenings.movie_id', $movieId)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
        }

        return view('admin.sales.movie', compact(
            'movies',
            'selectedMovie',
            'movieStats',
            'screeningSales',
            'dailySales'
        ));
    }

    /**
     * Muestra estadísticas detalladas por proyección
     */
    public function screeningReport(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Lista de proyecciones para el selector
        $screenings = Screening::with('movie')
            ->whereDate('screening_date', '>=', Carbon::now()->subDays(7))
            ->orderBy('screening_date')
            ->orderBy('screening_time')
            ->get();

        // Proyección seleccionada
        $screeningId = $request->screening_id;
        $selectedScreening = null;
        $screeningStats = null; // Inicializar como null
        $tickets = collect(); // Inicializar como colección vacía

        if ($screeningId) {
            $selectedScreening = Screening::with('movie')->findOrFail($screeningId);

            // Estadísticas de la proyección
            $screeningStats = Ticket::select(
                DB::raw('SUM(price) as total_sales'),
                DB::raw('COUNT(id) as tickets_count')
            )
                ->where('screening_id', $screeningId)
                ->first();

            // Tickets individuales de esa proyección
            $tickets = Ticket::with(['user', 'seat'])
                ->where('screening_id', $screeningId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.sales.screening', compact(
            'screenings',
            'selectedScreening',
            'screeningStats',
            'tickets'
        ));
    }
}
