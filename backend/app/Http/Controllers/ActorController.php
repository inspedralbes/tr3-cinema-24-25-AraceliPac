<?php

// app/Http/Controllers/ActorController.php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    /**
     * Mostra una llista de tots els actors.
     */
    public function index()
    {
        $actors = Actor::all(); // Obté tots els actors
        return response()->json($actors); // Retorna la llista d'actors en format JSON
    }

    /**
     * Mostra un actor específic.
     */
    public function show($id)
    {
        $actor = Actor::find($id); // Busca l'actor per ID
        if (!$actor) {
            return response()->json(['message' => 'Actor no trobat'], 404); // Retorna error si no es troba
        }
        return response()->json($actor); // Retorna l'actor en format JSON
    }

    /**
     * Crea un nou actor.
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
            'image' => 'nullable|string',
        ]);

        // Crea l'actor
        $actor = Actor::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'birth_date' => $request->birth_date,
            'nationality' => $request->nationality,
            'bio' => $request->bio,
            'image' => $request->image,
        ]);

        return response()->json($actor, 201); // Retorna l'actor creat en format JSON
    }

    /**
     * Actualitza un actor existent.
     */
    public function update(Request $request, $id)
    {
        $actor = Actor::find($id); // Busca l'actor per ID
        if (!$actor) {
            return response()->json(['message' => 'Actor no trobat'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        // Actualitza l'actor
        if ($request->has('name')) {
            $actor->name = $request->name;
        }
        if ($request->has('lastname')) {
            $actor->lastname = $request->lastname;
        }
        if ($request->has('birth_date')) {
            $actor->birth_date = $request->birth_date;
        }
        if ($request->has('nationality')) {
            $actor->nationality = $request->nationality;
        }
        if ($request->has('bio')) {
            $actor->bio = $request->bio;
        }
        if ($request->has('image')) {
            $actor->image = $request->image;
        }
        $actor->save();

        return response()->json($actor); // Retorna l'actor actualitzat en format JSON
    }

    /**
     * Elimina un actor.
     */
    public function destroy($id)
    {
        $actor = Actor::find($id); // Busca l'actor per ID
        if (!$actor) {
            return response()->json(['message' => 'Actor no trobat'], 404); // Retorna error si no es troba
        }

        $actor->delete(); // Elimina l'actor
        return response()->json(['message' => 'Actor eliminat correctament']); // Retorna missatge d'èxit
    }
}
