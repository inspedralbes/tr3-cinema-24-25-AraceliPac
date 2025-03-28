<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
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
     * Muestra la página de gestión de usuarios con filtros
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Obtenir els rols per al selector de filtre
        $roles = Role::all();

        // Començar la consulta
        $query = User::with('role');

        // Aplicar filtros si se proporcionan
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Obtener los resultados paginados
        $users = $query->orderBy('name')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $users->appends($request->all());

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario
     */
    public function store(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Validar las datos de la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|string',
        ]);

        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role_id' => $request->role_id,
                'image' => $request->image,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuari creat amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No s\'ha pogut crear l\'usuari: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de un usuario
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $user = User::with('role')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza un usuario existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $user = User::findOrFail($id);

        // Validar los datos de la solicitud
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|string',
        ];

        // Añadir validación de contraseña solo si se ha proporcionado
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        try {
            // Preparar los datos para actualizar
            $data = [
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $request->role_id,
                'image' => $request->image,
            ];

            // Actualizar la contraseña solo si se ha proporcionado
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Actualizar el usuario
            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuari actualitzat amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No s\'ha pogut actualitzar l\'usuari: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Elimina un usuario
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        try {
            $user = User::findOrFail($id);

            // Verificar que no se eliminen usuarios admin importantes
            if ($user->role_id == 1 && User::where('role_id', 1)->count() <= 1) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'No es pot eliminar l\'últim usuari administrador del sistema.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuari eliminat amb èxit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No s\'ha pogut eliminar l\'usuari: ' . $e->getMessage());
        }
    }
}
