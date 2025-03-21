<?php

// app/Http/Controllers/GenreController.php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Mostra una llista de tots els gèneres.
     */
    public function index()
    {
        $genres = Genre::all(); // Obté tots els gèneres
        return response()->json($genres); // Retorna la llista de gèneres en format JSON
    }

    /**
     * Mostra un gènere específic.
     */
    public function show($id)
    {
        $genre = Genre::find($id); // Busca el gènere per ID
        if (!$genre) {
            return response()->json(['message' => 'Gènere no trobat'], 404); // Retorna error si no es troba
        }
        return response()->json($genre); // Retorna el gènere en format JSON
    }

    /**
     * Crea un nou gènere.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'required|string|max:255|unique:genres',
            'description' => 'nullable|string',
        ]);

        // Crea el gènere
        $genre = Genre::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($genre, 201); // Retorna el gènere creat en format JSON
    }

    /**
     * Actualitza un gènere existent.
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::find($id); // Busca el gènere per ID
        if (!$genre) {
            return response()->json(['message' => 'Gènere no trobat'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string',
        ]);

        // Actualitza el gènere
        if ($request->has('name')) {
            $genre->name = $request->name;
        }
        if ($request->has('description')) {
            $genre->description = $request->description;
        }
        $genre->save();

        return response()->json($genre); // Retorna el gènere actualitzat en format JSON
    }

    /**
     * Elimina un gènere.
     */
    public function destroy($id)
    {
        $genre = Genre::find($id); // Busca el gènere per ID
        if (!$genre) {
            return response()->json(['message' => 'Gènere no trobat'], 404); // Retorna error si no es troba
        }

        $genre->delete(); // Elimina el gènere
        return response()->json(['message' => 'Gènere eliminat correctament']); // Retorna missatge d'èxit
    }
}
