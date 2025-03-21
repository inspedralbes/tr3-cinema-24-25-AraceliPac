<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Actor;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Listar todas las películas con información básica de actores.
     */
    public function index()
    {
        // Obtener todas las películas con información básica de actores (id, nombre, apellido)
        $movies = Movie::with(['genre', 'director', 'actors' => function ($query) {
            $query->select('actors.id', 'actors.name', 'actors.lastname'); // Especifica las columnas de la tabla actors
        }])->get();

        return response()->json($movies);
    }

    /**
     * Obtener una película específica con todos los detalles de los actores.
     */
    public function show($id)
    {
        // Obtener la película con todos los detalles de los actores
        $movie = Movie::with(['genre', 'director', 'actors'])->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        return response()->json($movie);
    }

    /**
     * Obtener solo los actores de una película específica.
     */
    public function getActors($id)
    {
        // Buscar la película
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Obtener los actores de la película con columnas específicas
        $actors = $movie->actors()->select('actors.id', 'actors.name', 'actors.lastname')->get();

        return response()->json($actors);
    }

    /**
     * Crear una nueva película.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'nullable|string|max:10',
            'duration' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
            'trailer' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'director_id' => 'required|exists:directors,id',
            'actor_ids' => 'nullable|array', // Lista de IDs de actores
            'actor_ids.*' => 'exists:actors,id', // Cada ID debe existir en la tabla actors
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

        return response()->json($movie, 201);
    }

    /**
     * Actualizar una película existente.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Validar los datos de la solicitud
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'nullable|string|max:10',
            'duration' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
            'trailer' => 'nullable|string',
            'genre_id' => 'sometimes|exists:genres,id',
            'director_id' => 'sometimes|exists:directors,id',
            'actor_ids' => 'nullable|array', // Lista de IDs de actores
            'actor_ids.*' => 'exists:actors,id', // Cada ID debe existir en la tabla actors
        ]);

        // Actualizar los campos de la película
        $movie->fill($request->only([
            'title',
            'description',
            'release_year',
            'rating',
            'duration',
            'image',
            'trailer',
            'genre_id',
            'director_id'
        ]));
        $movie->save();

        // Sincronizar los actores asociados a la película
        if ($request->has('actor_ids')) {
            $movie->actors()->sync($request->actor_ids);
        }

        return response()->json($movie);
    }

    /**
     * Eliminar una película.
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        $movie->delete();
        return response()->json(['message' => 'Película eliminada correctamente']);
    }
}
