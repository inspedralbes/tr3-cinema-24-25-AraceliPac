<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Genre;

class GenreController extends Controller
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
     * Muestra la lista de géneros
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Consulta base
        $query = Genre::query();

        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Ordenar
        $query->orderBy('name');

        // Paginar resultados
        $genres = $query->paginate(10);

        return view('admin.settings.genres.index', compact('genres'));
    }

    /**
     * Muestra el formulario para crear un nuevo género
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        return view('admin.settings.genres.create');
    }

    /**
     * Almacena un nuevo género
     */
    public function store(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres',
            'description' => 'nullable|string',
        ]);

        // Crear género
        Genre::create($validated);

        return redirect()->route('admin.settings.genres.index')
            ->with('success', 'Gènere creat amb èxit');
    }

    /**
     * Muestra los detalles de un género
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $genre = Genre::findOrFail($id);

        // Opcional: cargar películas relacionadas
        // $genre->load('movies');

        return view('admin.settings.genres.show', compact('genre'));
    }

    /**
     * Muestra el formulario para editar un género
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $genre = Genre::findOrFail($id);

        return view('admin.settings.genres.edit', compact('genre'));
    }

    /**
     * Actualiza un género existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Buscar género
        $genre = Genre::findOrFail($id);

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $id,
            'description' => 'nullable|string',
        ]);

        // Actualizar género
        $genre->update($validated);

        return redirect()->route('admin.settings.genres.index')
            ->with('success', 'Gènere actualitzat amb èxit');
    }

    /**
     * Elimina un género
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $genre = Genre::findOrFail($id);

        // Verificar si tiene películas asociadas
        // Si tienes una relación en el modelo, podrías hacer:
        // if ($genre->movies()->count() > 0) {
        //     return redirect()->route('admin.settings.genres.index')
        //         ->with('error', 'No es pot eliminar el gènere perquè té pel·lícules associades');
        // }

        // Eliminar género
        $genre->delete();

        return redirect()->route('admin.settings.genres.index')
            ->with('success', 'Gènere eliminat amb èxit');
    }
}
