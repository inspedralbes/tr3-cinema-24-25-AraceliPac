<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }

        // Validar según qué campos se estén actualizando
        $rules = [];

        // Campos básicos
        if ($request->has('name')) $rules['name'] = 'string|max:255';
        if ($request->has('last_name')) $rules['last_name'] = 'string|max:255';
        if ($request->has('email')) $rules['email'] = 'string|email|max:255|unique:users,email,' . $user->id;
        if ($request->has('phone')) $rules['phone'] = 'string|max:255';

        // Contraseña
        if ($request->has('password')) {
            $rules['password'] = 'string|min:8';
            $rules['current_password'] = 'required|string|min:8';
            $rules['password_confirmation'] = 'required|string|min:8';

            // Verificar la contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'La contrasenya actual és incorrecta'], 422);
            }
        }

        // Imagen
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|max:2048';
        }

        // Validar los datos
        $validatedData = $request->validate($rules);

        // Actualizar campos básicos
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('last_name')) $user->last_name = $request->last_name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('phone')) $user->phone = $request->phone;

        // Actualizar contraseña si se proporcionó
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        // Actualizar imagen si se proporcionó
        if ($request->hasFile('image')) {
            // Opcional: eliminar imagen anterior
            // if ($user->image && Storage::disk('public')->exists($user->image)) {
            //     Storage::disk('public')->delete($user->image);
            // }

            $user->image = $request->file('image')->store('users', 'public');
        }

        $user->save();

        return response()->json($user);
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
