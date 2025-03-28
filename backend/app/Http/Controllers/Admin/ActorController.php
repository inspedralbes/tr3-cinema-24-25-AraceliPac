<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Actor;

class ActorController extends Controller
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
     * Muestra la lista de actores
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Consulta base
        $query = Actor::query();

        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('nationality', 'like', "%{$search}%");
            });
        }

        // Ordenar
        $query->orderBy('name');

        // Paginar resultados
        $actors = $query->paginate(10);

        return view('admin.settings.actors.index', compact('actors'));
    }

    /**
     * Muestra el formulario para crear un nuevo actor
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        return view('admin.settings.actors.create');
    }

    /**
     * Almacena un nuevo actor
     */
    public function store(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        // Crear actor
        Actor::create($validated);

        return redirect()->route('admin.settings.actors.index')
            ->with('success', 'Actor creat amb èxit');
    }

    /**
     * Muestra los detalles de un actor
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $actor = Actor::findOrFail($id);

        // Opcional: cargar películas relacionadas
        // $actor->load('movies');

        return view('admin.settings.actors.show', compact('actor'));
    }

    /**
     * Muestra el formulario para editar un actor
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $actor = Actor::findOrFail($id);

        return view('admin.settings.actors.edit', compact('actor'));
    }

    /**
     * Actualiza un actor existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Buscar actor
        $actor = Actor::findOrFail($id);

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        // Actualizar actor
        $actor->update($validated);

        return redirect()->route('admin.settings.actors.index')
            ->with('success', 'Actor actualitzat amb èxit');
    }

    /**
     * Elimina un actor
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $actor = Actor::findOrFail($id);

        // Verificar si tiene películas asociadas
        // Si tienes una relación en el modelo, podrías hacer:
        // if ($actor->movies()->count() > 0) {
        //     return redirect()->route('admin.settings.actors.index')
        //         ->with('error', 'No es pot eliminar l\'actor perquè té pel·lícules associades');
        // }

        // Eliminar actor
        $actor->delete();

        return redirect()->route('admin.settings.actors.index')
            ->with('success', 'Actor eliminat amb èxit');
    }
}
