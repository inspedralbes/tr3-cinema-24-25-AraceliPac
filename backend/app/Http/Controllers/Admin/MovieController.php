<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;
use App\Models\Actor;

class MovieController extends Controller
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
     * Muestra la página de gestión de películas
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

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
     * Muestra el formulario para crear una nueva película
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $genres = Genre::all();
        $directors = Director::all();
        $actors = Actor::all();
        return view('admin.movies.create', compact('genres', 'directors', 'actors'));
    }

    /**
     * Almacena una nueva película
     */
    public function store(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

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
     * Muestra detalles de una película
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Buscar la película con sus relaciones
        $movie = Movie::with(['genre', 'director', 'actors'])->findOrFail($id);

        return view('admin.movies.show', compact('movie'));
    }

    /**
     * Muestra formulario para editar una película
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

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
     * Actualiza una película existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

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
     * Elimina una película
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

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
}
