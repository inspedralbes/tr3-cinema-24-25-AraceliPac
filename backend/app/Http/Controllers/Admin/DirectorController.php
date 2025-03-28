<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Director;

class DirectorController extends Controller
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
     * Muestra la lista de directores
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Consulta base
        $query = Director::query();

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
        $directors = $query->paginate(10);

        return view('admin.settings.directors.index', compact('directors'));
    }

    /**
     * Muestra el formulario para crear un nuevo director
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        return view('admin.settings.directors.create');
    }

    /**
     * Almacena un nuevo director
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
        ]);

        // Crear director
        Director::create($validated);

        return redirect()->route('admin.settings.directors.index')
            ->with('success', 'Director creat amb èxit');
    }

    /**
     * Muestra los detalles de un director
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $director = Director::findOrFail($id);

        // Opcional: cargar películas relacionadas
        // $director->load('movies');

        return view('admin.settings.directors.show', compact('director'));
    }

    /**
     * Muestra el formulario para editar un director
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $director = Director::findOrFail($id);

        return view('admin.settings.directors.edit', compact('director'));
    }

    /**
     * Actualiza un director existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Buscar director
        $director = Director::findOrFail($id);

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        // Actualizar director
        $director->update($validated);

        return redirect()->route('admin.settings.directors.index')
            ->with('success', 'Director actualitzat amb èxit');
    }

    /**
     * Elimina un director
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $director = Director::findOrFail($id);

        // Verificar si tiene películas asociadas
        // Si tienes una relación en el modelo, podrías hacer:
        // if ($director->movies()->count() > 0) {
        //     return redirect()->route('admin.settings.directors.index')
        //         ->with('error', 'No es pot eliminar el director perquè té pel·lícules associades');
        // }

        // Eliminar director
        $director->delete();

        return redirect()->route('admin.settings.directors.index')
            ->with('success', 'Director eliminat amb èxit');
    }
}
