<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;
use App\Models\Actor;
use App\Models\Screening;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Seat;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Gestiona la petició d'inici de sessió administrativa
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Verificar si és administrador
            if (Auth::user()->role_id != 1) {
                Auth::logout();
                return back()->withErrors(['email' => 'No tens permisos d\'administrador']);
            }

            return redirect()->intended('/admin');
        }

        return back()->withErrors(['email' => 'Credencials invàlides']);
    }

    /**
     * Tanca la sessió d'administració
     */
    public function logout(Request $request)
    {
        // Tancar la sessió web
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Verifica l'accés d'administrador
     */
    private function checkAdminAccess(Request $request)
    {
        // En aplicacions web, l'usuari ja hauria d'estar autenticat pel middleware 'auth'
        // però ho comprovem per seguretat
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Comprova si l'usuari és administrador
        if (Auth::user()->role_id != 1) {
            return redirect('/')->with('error', 'No tens permisos d\'administrador');
        }

        return null; // Accés permès
    }

    /**
     * Fa una petició API amb l'autenticació de sessió
     */
    private function apiRequest($endpoint, $params = [])
    {
        try {
            // En aplicacions Laravel, les peticions HTTP mantenen la cookie de sessió
            // això permetrà que l'API utilitzi l'autenticació web si està configurada així
            $response = Http::get(url($endpoint), $params);
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('API request error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mostra el dashboard d'administració
     */
    public function index(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }
        return view('admin.home');
    }

    // CRUD SCREENINGS
    /**
     * Mostrar la página de proyecciones con filtros
     */
    public function screenings(Request $request)
    {
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
    public function createScreening()
    {
        $movies = Movie::orderBy('title')->get();

        return view('admin.screenings.create', compact('movies'));
    }

    /**
     * Almacenar una nueva proyección
     */
    public function storeScreening(Request $request)
    {
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
    public function showScreening($id)
    {
        $screening = Screening::with('movie')->findOrFail($id);

        return view('admin.screenings.show', compact('screening'));
    }

    /**
     * Mostrar formulario para editar una proyección
     */
    public function editScreening($id)
    {
        $screening = Screening::findOrFail($id);
        $movies = Movie::orderBy('title')->get();

        return view('admin.screenings.edit', compact('screening', 'movies'));
    }

    /**
     * Actualizar una proyección existente
     */
    public function updateScreening(Request $request, $id)
    {
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
    public function destroyScreening($id)
    {
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



    /**
     * Mostra la pàgina de gestió d'usuaris
     */
    /**
     * Mostra la pàgina de gestió d'usuaris amb filtres
     */
    public function users(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        // Obtenir els rols per al selector de filtre
        $roles = \App\Models\Role::all();

        // Començar la consulta
        $query = User::with('role');

        // Aplicar filtres si es proporcionen
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Obtenir els resultats paginats
        $users = $query->orderBy('name')->paginate(10);

        // Mantenir els paràmetres de filtre en la paginació
        $users->appends($request->all());

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Mostra el formulari per crear un nou usuari
     */
    public function createUser(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        $roles = \App\Models\Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Emmagatzema un nou usuari
     */
    public function storeUser(Request $request)
    {
        // Validar les dades de la sol·licitud
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
            // Crear l'usuari
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
     * Mostra els detalls d'un usuari
     */
    public function showUser($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostra el formulari per editar un usuari
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = \App\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualitza un usuari existent
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validar les dades de la sol·licitud
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|string',
        ];

        // Afegir validació de contrasenya només si s'ha proporcionat
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        try {
            // Preparar les dades per actualitzar
            $data = [
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $request->role_id,
                'image' => $request->image,
            ];

            // Actualitzar la contrasenya només si s'ha proporcionat
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Actualitzar l'usuari
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
     * Elimina un usuari
     */
    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // Verificar que no s'eliminin usuaris admin importants
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

    /**
     * Mostra la pàgina de configuració
     */
    public function settings(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.settings.index');
    }
    // CRUD MOVIES
    /**
     * Mostra la pàgina de gestió de pel·lícules
     */
    public function movies(Request $request)
    {
        // Obtener los géneros para el selector de filtro
        $genres = Genre::all();

        // Comenzar la consulta
        $query = Movie::with(['genre', 'director']);

        // Aplicar filtros si se proporcionan
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('genre_id')) {
            $query->where('genre_id', $request->genre_id);
        }

        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // Obtener los resultados paginados
        $movies = $query->orderBy('title')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $movies->appends($request->all());

        return view('admin.movies.index', compact('movies', 'genres'));
    }

    /**
     * Mostra el formulari per crear una nova pel·lícula
     */
    public function createMovie(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }


        $genres = Genre::all();
        $directors = Director::all();
        $actors = Actor::all();
        return view('admin.movies.create', compact('genres', 'directors', 'actors'));
    }
    public function storeMovie(Request $request)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'nullable|string|max:10',
            'duration' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
            'trailer' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'director_id' => 'required|exists:directors,id',
            'actor_ids' => 'nullable|array',
            'actor_ids.*' => 'exists:actors,id',
        ]);

        // Crear la película
        $movie = Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'release_year' => $request->release_year,
            'rating' => $request->rating,
            'duration' => $request->duration,
            'image' => $request->image,
            'trailer' => $request->trailer,
            'genre_id' => $request->genre_id,
            'director_id' => $request->director_id,
        ]);

        // Asociar los actores a la película
        if ($request->has('actor_ids')) {
            $movie->actors()->attach($request->actor_ids);
        }

        return redirect()->route('admin.movies.index')
            ->with('success', 'Pel·lícula creada amb èxit.');
    }
    /**
     * Eliminar una película
     */
    public function destroyMovie($id)
    {
        try {
            // Buscar la película por ID
            $movie = Movie::findOrFail($id);

            // Eliminar los actores asociados (relación many-to-many)
            $movie->actors()->detach();

            // Eliminar la película
            $movie->delete();

            return redirect()->route('admin.movies.index')
                ->with('success', 'Pel·lícula eliminada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.movies.index')
                ->with('error', 'No s\'ha pogut eliminar la pel·lícula: ' . $e->getMessage());
        }
    }
    /**
     * Mostrar formulario para editar una película
     */
    public function editMovie($id)
    {
        // Buscar la película
        $movie = Movie::findOrFail($id);

        // Obtener géneros, directores y actores para los select del formulario
        $genres = Genre::all();
        $directors = Director::all();
        $actors = Actor::all();

        // Obtener IDs de actores ya asignados a esta película
        $movieActorIds = $movie->actors->pluck('id')->toArray();

        return view('admin.movies.edit', compact('movie', 'genres', 'directors', 'actors', 'movieActorIds'));
    }
    /**
     * Actualizar una película existente
     */
    public function updateMovie(Request $request, $id)
    {
        try {
            // Buscar la película
            $movie = Movie::findOrFail($id);

            // Validar los datos de la solicitud
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'rating' => 'nullable|string|max:10',
                'duration' => 'nullable|integer|min:1',
                'image' => 'nullable|string',
                'trailer' => 'nullable|string',
                'genre_id' => 'required|exists:genres,id',
                'director_id' => 'required|exists:directors,id',
                'actor_ids' => 'nullable|array',
                'actor_ids.*' => 'exists:actors,id',
            ]);

            // Actualizar los campos de la película
            $movie->update([
                'title' => $request->title,
                'description' => $request->description,
                'release_year' => $request->release_year,
                'rating' => $request->rating,
                'duration' => $request->duration,
                'image' => $request->image,
                'trailer' => $request->trailer,
                'genre_id' => $request->genre_id,
                'director_id' => $request->director_id,
            ]);

            // Sincronizar los actores asociados a la película
            if ($request->has('actor_ids')) {
                $movie->actors()->sync($request->actor_ids);
            } else {
                $movie->actors()->detach();
            }

            return redirect()->route('admin.movies.index')
                ->with('success', 'Pel·lícula actualitzada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No s\'ha pogut actualitzar la pel·lícula: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Mostrar detalles de una película
     */
    public function showMovie($id)
    {
        // Buscar la película con sus relaciones
        $movie = Movie::with(['genre', 'director', 'actors'])->findOrFail($id);

        return view('admin.movies.show', compact('movie'));
    }
    // CRUD TICKETS
    // CRUD TICKETS

    /**
     * Mostrar el listado de entradas
     */
    public function tickets(Request $request)
    {
        // Cargar datos para filtros
        $users = User::orderBy('name')->get();
        $movies = Movie::orderBy('title')->get();

        // Comenzar la consulta
        $query = Ticket::with(['user', 'screening.movie', 'seat']);

        // Aplicar filtros si se proporcionan
        if ($request->filled('ticket_number')) {
            $query->where('ticket_number', 'like', '%' . $request->ticket_number . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('screening', function ($q) use ($request) {
                $q->whereHas('movie', function ($q) use ($request) {
                    $q->where('id', $request->movie_id);
                });
            });
        }

        // Obtener los resultados paginados
        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $tickets->appends($request->all());

        return view('admin.tickets.index', compact('tickets', 'users', 'movies'));
    }

    /**
     * Mostrar el formulario para crear una nueva entrada
     */
    public function createTicket()
    {
        $users = User::orderBy('name')->get();
        $screenings = Screening::with('movie')
            ->where('screening_date', '>=', now()->format('Y-m-d'))
            ->orderBy('screening_date')
            ->orderBy('screening_time')
            ->get();
        $seats = Seat::orderBy('row')->orderBy('column')->get();

        return view('admin.tickets.create', compact('users', 'screenings', 'seats'));
    }

    /**
     * Almacenar una nueva entrada
     */
    public function storeTicket(Request $request)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'ticket_number' => 'required|string|max:20|unique:tickets',
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            // Verificar que el asiento no esté ya ocupado para esta proyección
            $existingTicket = Ticket::where('screening_id', $request->screening_id)
                ->where('seat_id', $request->seat_id)
                ->first();

            if ($existingTicket) {
                return redirect()->back()
                    ->with('error', 'Aquest seient ja està ocupat per a aquesta projecció.')
                    ->withInput();
            }

            // Crear la entrada con un QR code simple (se podría mejorar con una librería real de QR)
            $qrCode = null; // Aquí se generaría un QR real

            $ticket = Ticket::create([
                'ticket_number' => $request->ticket_number,
                'qr_code' => $qrCode,
                'user_id' => $request->user_id,
                'screening_id' => $request->screening_id,
                'seat_id' => $request->seat_id,
                'price' => $request->price,
            ]);

            return redirect()->route('admin.tickets')
                ->with('success', 'Entrada creada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de una entrada
     */
    public function showTicket($id)
    {
        $ticket = Ticket::with(['user', 'screening.movie', 'seat'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Mostrar formulario para editar una entrada
     */
    public function editTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $users = User::orderBy('name')->get();
        $screenings = Screening::with('movie')->orderBy('screening_date')->orderBy('screening_time')->get();
        $seats = Seat::orderBy('row')->orderBy('column')->get();

        return view('admin.tickets.edit', compact('ticket', 'users', 'screenings', 'seats'));
    }

    /**
     * Actualizar una entrada existente
     */
    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Validar los datos de la solicitud
        $validated = $request->validate([
            'ticket_number' => 'required|string|max:20|unique:tickets,ticket_number,' . $id,
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            // Verificar que el asiento no esté ya ocupado para esta proyección (si es diferente al actual)
            if ($request->seat_id != $ticket->seat_id || $request->screening_id != $ticket->screening_id) {
                $existingTicket = Ticket::where('screening_id', $request->screening_id)
                    ->where('seat_id', $request->seat_id)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingTicket) {
                    return redirect()->back()
                        ->with('error', 'Aquest seient ja està ocupat per a aquesta projecció.')
                        ->withInput();
                }
            }

            // Actualizar la entrada
            $ticket->update([
                'ticket_number' => $request->ticket_number,
                'user_id' => $request->user_id,
                'screening_id' => $request->screening_id,
                'seat_id' => $request->seat_id,
                'price' => $request->price,
            ]);

            return redirect()->route('admin.tickets')
                ->with('success', 'Entrada actualitzada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar una entrada
     */
    public function destroyTicket($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();

            return redirect()->route('admin.tickets')
                ->with('success', 'Entrada eliminada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tickets')
                ->with('error', 'No s\'ha pogut eliminar l\'entrada: ' . $e->getMessage());
        }
    }
    /**
     * Mostra la pàgina de perfil de l'usuari actual
     */
    public function profile(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        // L'usuari autenticat es recupera automàticament amb Auth::user()
        // i s'enviarà a la vista

        return view('admin.profile');
    }

    /**
     * Actualitza la contrasenya de l'usuari actual
     */
    /**
     * Actualitza la contrasenya de l'usuari actual
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();

            // Validar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'La contrasenya actual és incorrecta.')
                    ->withInput();
            }

            // Validar nueva contraseña
            if ($request->password != $request->password_confirmation) {
                return redirect()->back()
                    ->with('error', 'Les contrasenyes no coincideixen.')
                    ->withInput();
            }

            if (strlen($request->password) < 8) {
                return redirect()->back()
                    ->with('error', 'La contrasenya ha de tenir almenys 8 caràcters.')
                    ->withInput();
            }

            // Actualizar directamente en la base de datos
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => bcrypt($request->password)]);

            return redirect()->route('admin.profile')
                ->with('password_success', 'Contrasenya canviada amb èxit.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No s\'ha pogut canviar la contrasenya: ' . $e->getMessage())
                ->withInput();
        }
    }
}
