<?php

// app/Http/Controllers/DirectorController.php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    /**
     * Mostra una llista de tots els directors.
     */
    public function index()
    {
        $directors = Director::all(); // Obté tots els directors
        return response()->json($directors); // Retorna la llista de directors en format JSON
    }

    /**
     * Mostra un director específic.
     */
    public function show($id)
    {
        $director = Director::find($id); // Busca el director per ID
        if (!$director) {
            return response()->json(['message' => 'Director no trobat'], 404); // Retorna error si no es troba
        }
        return response()->json($director); // Retorna el director en format JSON
    }

    /**
     * Crea un nou director.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        // Crea el director
        $director = Director::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'birth_date' => $request->birth_date,
            'nationality' => $request->nationality,
            'bio' => $request->bio,
        ]);

        return response()->json($director, 201); // Retorna el director creat en format JSON
    }

    /**
     * Actualitza un director existent.
     */
    public function update(Request $request, $id)
    {
        $director = Director::find($id); // Busca el director per ID
        if (!$director) {
            return response()->json(['message' => 'Director no trobat'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        // Actualitza el director
        if ($request->has('name')) {
            $director->name = $request->name;
        }
        if ($request->has('lastname')) {
            $director->lastname = $request->lastname;
        }
        if ($request->has('birth_date')) {
            $director->birth_date = $request->birth_date;
        }
        if ($request->has('nationality')) {
            $director->nationality = $request->nationality;
        }
        if ($request->has('bio')) {
            $director->bio = $request->bio;
        }
        $director->save();

        return response()->json($director); // Retorna el director actualitzat en format JSON
    }

    /**
     * Elimina un director.
     */
    public function destroy($id)
    {
        $director = Director::find($id); // Busca el director per ID
        if (!$director) {
            return response()->json(['message' => 'Director no trobat'], 404); // Retorna error si no es troba
        }

        $director->delete(); // Elimina el director
        return response()->json(['message' => 'Director eliminat correctament']); // Retorna missatge d'èxit
    }
}
