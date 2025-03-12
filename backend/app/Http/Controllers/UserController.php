<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); // Obté tots els usuaris
        return response()->json($users); // Retorna la llista d'usuaris en format JSON
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Crea l'usuari
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Xifra la contrasenya
        ]);

        return response()->json($user, 201); // Retorna l'usuari creat en format JSON
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id); // Busca l'usuari per ID
        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404); // Retorna error si no es troba
        }
        return response()->json($user); // Retorna l'usuari en format JSON

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id); // Busca l'usuari per ID
        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
        ]);

        // Actualitza l'usuari
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password); // Xifra la nova contrasenya
        }
        $user->save();

        return response()->json($user); // Retorna l'usuari actualitzat en format JSON
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id); // Busca l'usuari per ID
        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404); // Retorna error si no es troba
        }

        $user->delete(); // Elimina l'usuari
        return response()->json(['message' => 'Usuari eliminat correctament']); // Retorna missatge d'èxit
    }
}
