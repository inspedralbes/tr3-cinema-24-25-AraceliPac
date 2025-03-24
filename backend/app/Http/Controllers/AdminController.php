<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;
use App\Models\Actor;
use App\Models\Screening;

class AdminController extends Controller
{
    /**
     * Gestiona la petició d'inici de sessió administrativa
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Verificar si és administrador
            if (Auth::user()->role_id != 1) {
                Auth::logout();
                return back()->withErrors(['email' => 'No tens permisos d\'administrador']);
            }

            return redirect()->intended('/admin');
        }

        return back()->withErrors(['email' => 'Credencials invàlides']);
    }

    /**
     * Tanca la sessió d'administració
     */
    public function logout(Request $request)
    {
        // Tancar la sessió web
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Verifica l'accés d'administrador
     */
    private function checkAdminAccess(Request $request)
    {
        // En aplicacions web, l'usuari ja hauria d'estar autenticat pel middleware 'auth'
        // però ho comprovem per seguretat
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Comprova si l'usuari és administrador
        if (Auth::user()->role_id != 1) {
            return redirect('/')->with('error', 'No tens permisos d\'administrador');
        }

        return null; // Accés permès
    }

    /**
     * Fa una petició API amb l'autenticació de sessió
     */
    private function apiRequest($endpoint, $params = [])
    {
        try {
            // En aplicacions Laravel, les peticions HTTP mantenen la cookie de sessió
            // això permetrà que l'API utilitzi l'autenticació web si està configurada així
            $response = Http::get(url($endpoint), $params);
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('API request error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mostra el dashboard d'administració
     */
    public function index(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }
        return view('admin.home');
    }

    // CRUD SCREENINGS
    /**
     * Mostrar la página de proyecciones con filtros
     */
    public function screenings(Request $request)
    {
        // Obtener películas para el selector de filtro
        $movies = Movie::orderBy('title')->get();

        // Comenzar la consulta
        $query = Screening::with('movie');

        // Aplicar filtros si se proporcionan
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('screening_date', $request->date);
        }

        // Obtener los resultados paginados
        $screenings = $query->orderBy('screening_date')->orderBy('screening_time')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $screenings->appends($request->all());

        return view('admin.screenings.index', compact('screenings', 'movies'));
    }

    /**
     * Mostrar formulario para crear una nueva proyección
     */
    public function createScreening()
    {
        $movies = Movie::orderBy('title')->get();

        return view('admin.screenings.create', compact('movies'));
    }

    /**
     * Almacenar una nueva proyección
     */
    public function storeScreening(Request $request)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screening_date' => 'required|date',
            'screening_time' => 'required',
        ]);

        try {
            // Verificar disponibilidad de horario (simplificado)
            $existingScreening = Screening::where('movie_id', $request->movie_id)
                ->whereDate('screening_date', $request->screening_date)
                ->whereTime('screening_time', $request->screening_time)
                ->first();

            if ($existingScreening) {
                return redirect()->back()
                    ->with('error', 'Ja existeix una projecció d\'aquesta pel·lícula en aquesta data i hora.')
                    ->withInput();
            }

            // Crear la proyección
            $screening = Screening::create([
                'movie_id' => $request->movie_id,
                'screening_date' => $request->screening_date,
                'screening_time' => $request->screening_time,
                'is_special_day' => $request->has('is_special_day'),
                'is_full' => $request->has('is_full'),
            ]);

            return redirect()->route('admin.screenings')
                ->with('success', 'Projecció creada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de una proyección
     */
    public function showScreening($id)
    {
        $screening = Screening::with('movie')->findOrFail($id);

        return view('admin.screenings.show', compact('screening'));
    }

    /**
     * Mostrar formulario para editar una proyección
     */
    public function editScreening($id)
    {
        $screening = Screening::findOrFail($id);
        $movies = Movie::orderBy('title')->get();

        return view('admin.screenings.edit', compact('screening', 'movies'));
    }

    /**
     * Actualizar una proyección existente
     */
    public function updateScreening(Request $request, $id)
    {
        $screening = Screening::findOrFail($id);

        // Validar los datos de la solicitud
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screening_date' => 'required|date',
            'screening_time' => 'required',
        ]);

        try {
            // Verificar disponibilidad si cambió la fecha/hora
            if ($request->screening_date != $screening->screening_date || $request->screening_time != $screening->screening_time) {
                $existingScreening = Screening::where('movie_id', $request->movie_id)
                    ->whereDate('screening_date', $request->screening_date)
                    ->whereTime('screening_time', $request->screening_time)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingScreening) {
                    return redirect()->back()
                        ->with('error', 'Ja existeix una projecció d\'aquesta pel·lícula en aquesta data i hora.')
                        ->withInput();
                }
            }

            // Actualizar la proyección
            $screening->update([
                'movie_id' => $request->movie_id,
                'screening_date' => $request->screening_date,
                'screening_time' => $request->screening_time,
                'is_special_day' => $request->has('is_special_day'),
                'is_full' => $request->has('is_full'),
            ]);

            return redirect()->route('admin.screenings')
                ->with('success', 'Projecció actualitzada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar una proyección
     */
    public function destroyScreening($id)
    {
        try {
            $screening = Screening::findOrFail($id);
            $screening->delete();

            return redirect()->route('admin.screenings')
                ->with('success', 'Projecció eliminada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.screenings')
                ->with('error', 'No s\'ha pogut eliminar la projecció: ' . $e->getMessage());
        }
    }

    /**
     * Mostra la pàgina de gestió d'entrades
     */
    public function tickets(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.tickets.index');
    }

    /**
     * Mostra el formulari per crear una nova entrada
     */
    public function createTicket(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.tickets.create');
    }

    /**
     * Mostra la pàgina de gestió d'usuaris
     */
    public function users(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.users.index');
    }

    /**
     * Mostra la pàgina de configuració
     */
    public function settings(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.settings.index');
    }
    /**
     * Mostra la pàgina de gestió de pel·lícules
     */
    public function movies(Request $request)
    {
        // Obtener los géneros para el selector de filtro
        $genres = Genre::all();

        // Comenzar la consulta
        $query = Movie::with(['genre', 'director']);

        // Aplicar filtros si se proporcionan
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('genre_id')) {
            $query->where('genre_id', $request->genre_id);
        }

        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // Obtener los resultados paginados
        $movies = $query->orderBy('title')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $movies->appends($request->all());

        return view('admin.movies.index', compact('movies', 'genres'));
    }

    /**
     * Mostra el formulari per crear una nova pel·lícula
     */
    public function createMovie(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }


        $genres = Genre::all();
        $directors = Director::all();
        $actors = Actor::all();
        return view('admin.movies.create', compact('genres', 'directors', 'actors'));
    }
    public function storeMovie(Request $request)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'nullable|string|max:10',
            'duration' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
            'trailer' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'director_id' => 'required|exists:directors,id',
            'actor_ids' => 'nullable|array',
            'actor_ids.*' => 'exists:actors,id',
        ]);

        // Crear la película
        $movie = Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'release_year' => $request->release_year,
            'rating' => $request->rating,
            'duration' => $request->duration,
            'image' => $request->image,
            'trailer' => $request->trailer,
            'genre_id' => $request->genre_id,
            'director_id' => $request->director_id,
        ]);

        // Asociar los actores a la película
        if ($request->has('actor_ids')) {
            $movie->actors()->attach($request->actor_ids);
        }

        return redirect()->route('admin.movies.index')
            ->with('success', 'Pel·lícula creada amb èxit.');
    }
    /**
     * Eliminar una película
     */
    public function destroyMovie($id)
    {
        try {
            // Buscar la película por ID
            $movie = Movie::findOrFail($id);

            // Eliminar los actores asociados (relación many-to-many)
            $movie->actors()->detach();

            // Eliminar la película
            $movie->delete();

            return redirect()->route('admin.movies.index')
                ->with('success', 'Pel·lícula eliminada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.movies.index')
                ->with('error', 'No s\'ha pogut eliminar la pel·lícula: ' . $e->getMessage());
        }
    }
    /**
     * Mostrar formulario para editar una película
     */
    public function editMovie($id)
    {
        // Buscar la película
        $movie = Movie::findOrFail($id);

        // Obtener géneros, directores y actores para los select del formulario
        $genres = Genre::all();
        $directors = Director::all();
        $actors = Actor::all();

        // Obtener IDs de actores ya asignados a esta película
        $movieActorIds = $movie->actors->pluck('id')->toArray();

        return view('admin.movies.edit', compact('movie', 'genres', 'directors', 'actors', 'movieActorIds'));
    }
    /**
     * Actualizar una película existente
     */
    public function updateMovie(Request $request, $id)
    {
        try {
            // Buscar la película
            $movie = Movie::findOrFail($id);

            // Validar los datos de la solicitud
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'rating' => 'nullable|string|max:10',
                'duration' => 'nullable|integer|min:1',
                'image' => 'nullable|string',
                'trailer' => 'nullable|string',
                'genre_id' => 'required|exists:genres,id',
                'director_id' => 'required|exists:directors,id',
                'actor_ids' => 'nullable|array',
                'actor_ids.*' => 'exists:actors,id',
            ]);

            // Actualizar los campos de la película
            $movie->update([
                'title' => $request->title,
                'description' => $request->description,
                'release_year' => $request->release_year,
                'rating' => $request->rating,
                'duration' => $request->duration,
                'image' => $request->image,
                'trailer' => $request->trailer,
                'genre_id' => $request->genre_id,
                'director_id' => $request->director_id,
            ]);

            // Sincronizar los actores asociados a la película
            if ($request->has('actor_ids')) {
                $movie->actors()->sync($request->actor_ids);
            } else {
                $movie->actors()->detach();
            }

            return redirect()->route('admin.movies.index')
                ->with('success', 'Pel·lícula actualitzada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No s\'ha pogut actualitzar la pel·lícula: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Mostrar detalles de una película
     */
    public function showMovie($id)
    {
        // Buscar la película con sus relaciones
        $movie = Movie::with(['genre', 'director', 'actors'])->findOrFail($id);

        return view('admin.movies.show', compact('movie'));
    }
}
