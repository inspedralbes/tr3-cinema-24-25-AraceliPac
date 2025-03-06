<?php

// app/Http/Controllers/MovieController.php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Mostra una llista de totes les pel·lícules.
     */
    public function index()
    {
        $movies = Movie::with(['genre', 'director'])->get(); // Obté totes les pel·lícules amb les relacions
        return response()->json($movies); // Retorna la llista de pel·lícules en format JSON
    }

    /**
     * Mostra una pel·lícula específica.
     */
    public function show($id)
    {
        $movie = Movie::with(['genre', 'director'])->find($id); // Busca la pel·lícula per ID amb les relacions
        if (!$movie) {
            return response()->json(['message' => 'Pel·lícula no trobada'], 404); // Retorna error si no es troba
        }
        return response()->json($movie); // Retorna la pel·lícula en format JSON
    }

    /**
     * Crea una nova pel·lícula.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
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
        ]);

        // Crea la pel·lícula
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

        return response()->json($movie, 201); // Retorna la pel·lícula creada en format JSON
    }

    /**
     * Actualitza una pel·lícula existent.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id); // Busca la pel·lícula per ID
        if (!$movie) {
            return response()->json(['message' => 'Pel·lícula no trobada'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
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
        ]);

        // Actualitza la pel·lícula
        if ($request->has('title')) {
            $movie->title = $request->title;
        }
        if ($request->has('description')) {
            $movie->description = $request->description;
        }
        if ($request->has('release_year')) {
            $movie->release_year = $request->release_year;
        }
        if ($request->has('rating')) {
            $movie->rating = $request->rating;
        }
        if ($request->has('duration')) {
            $movie->duration = $request->duration;
        }
        if ($request->has('image')) {
            $movie->image = $request->image;
        }
        if ($request->has('trailer')) {
            $movie->trailer = $request->trailer;
        }
        if ($request->has('genre_id')) {
            $movie->genre_id = $request->genre_id;
        }
        if ($request->has('director_id')) {
            $movie->director_id = $request->director_id;
        }
        $movie->save();

        return response()->json($movie); // Retorna la pel·lícula actualitzada en format JSON
    }

    /**
     * Elimina una pel·lícula.
     */
    public function destroy($id)
    {
        $movie = Movie::find($id); // Busca la pel·lícula per ID
        if (!$movie) {
            return response()->json(['message' => 'Pel·lícula no trobada'], 404); // Retorna error si no es troba
        }

        $movie->delete(); // Elimina la pel·lícula
        return response()->json(['message' => 'Pel·lícula eliminada correctament']); // Retorna missatge d'èxit
    }
}
