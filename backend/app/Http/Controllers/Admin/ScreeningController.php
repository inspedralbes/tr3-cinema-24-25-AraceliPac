<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Screening;
use App\Models\Movie;

class ScreeningController extends Controller
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
     * Mostrar la página de proyecciones con filtros
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Obtener películas para el selector de filtro
        $movies = Movie::orderBy('title')->get();

        // Comenzar la consulta
        $query = Screening::with('movie');

        // Aplicar filtros si se proporcionan
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('screening_date', $request->date);
        }

        // Obtener los resultados paginados
        $screenings = $query->orderBy('screening_date')->orderBy('screening_time')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $screenings->appends($request->all());

        return view('admin.screenings.index', compact('screenings', 'movies'));
    }

    /**
     * Mostrar formulario para crear una nueva proyección
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $movies = Movie::orderBy('title')->get();

        return view('admin.screenings.create', compact('movies'));
    }

    /**
     * Almacenar una nueva proyección
     */
    public function store(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Validar los datos de la solicitud
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screening_date' => 'required|date',
            'screening_time' => 'required',
        ]);

        try {
            // Verificar disponibilidad de horario
            $existingScreening = Screening::where('movie_id', $request->movie_id)
                ->whereDate('screening_date', $request->screening_date)
                ->whereTime('screening_time', $request->screening_time)
                ->first();

            if ($existingScreening) {
                return redirect()->back()
                    ->with('error', 'Ja existeix una projecció d\'aquesta pel·lícula en aquesta data i hora.')
                    ->withInput();
            }

            // Crear la proyección
            $screening = Screening::create([
                'movie_id' => $request->movie_id,
                'screening_date' => $request->screening_date,
                'screening_time' => $request->screening_time,
                'is_special_day' => $request->has('is_special_day') ? 1 : 0,
                'is_full' => $request->has('is_full') ? 1 : 0,
            ]);

            return redirect()->route('admin.screenings')
                ->with('success', 'Projecció creada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de una proyección
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $screening = Screening::with('movie')->findOrFail($id);

        return view('admin.screenings.show', compact('screening'));
    }

    /**
     * Mostrar formulario para editar una proyección
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $screening = Screening::findOrFail($id);
        $movies = Movie::orderBy('title')->get();

        return view('admin.screenings.edit', compact('screening', 'movies'));
    }

    /**
     * Actualizar una proyección existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $screening = Screening::findOrFail($id);

        // Validar los datos de la solicitud
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screening_date' => 'required|date',
            'screening_time' => 'required',
        ]);

        try {
            // Verificar disponibilidad si cambió la fecha/hora
            if ($request->screening_date != $screening->screening_date || $request->screening_time != $screening->screening_time) {
                $existingScreening = Screening::where('movie_id', $request->movie_id)
                    ->whereDate('screening_date', $request->screening_date)
                    ->whereTime('screening_time', $request->screening_time)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingScreening) {
                    return redirect()->back()
                        ->with('error', 'Ja existeix una projecció d\'aquesta pel·lícula en aquesta data i hora.')
                        ->withInput();
                }
            }

            // Actualizar la proyección
            $screening->update([
                'movie_id' => $request->movie_id,
                'screening_date' => $request->screening_date,
                'screening_time' => $request->screening_time,
                'is_special_day' => $request->has('is_special_day'),
                'is_full' => $request->has('is_full'),
            ]);

            return redirect()->route('admin.screenings')
                ->with('success', 'Projecció actualitzada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar una proyección
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        try {
            $screening = Screening::findOrFail($id);
            $screening->delete();

            return redirect()->route('admin.screenings')
                ->with('success', 'Projecció eliminada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.screenings')
                ->with('error', 'No s\'ha pogut eliminar la projecció: ' . $e->getMessage());
        }
    }
}
