<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

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

    // Els altres mètodes romanen iguals...
    /**
     * Mostra la pàgina de gestió de pel·lícules
     */
    public function movies(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.movies.index');
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


        return view('admin.movies.create');
    }

    /**
     * Mostra la pàgina de gestió de projeccions
     */
    public function screenings(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.screenings.index');
    }

    /**
     * Mostra el formulari per crear una nova projecció
     */
    public function createScreening(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.screenings.create');
    }

    /**
     * Mostra la pàgina de gestió d'entrades
     */
    public function tickets(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.tickets.index');
    }

    /**
     * Mostra el formulari per crear una nova entrada
     */
    public function createTicket(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.tickets.create');
    }

    /**
     * Mostra la pàgina de gestió d'usuaris
     */
    public function users(Request $request)
    {
        $checkResult = $this->checkAdminAccess($request);
        if ($checkResult) {
            return $checkResult;
        }

        return view('admin.users.index');
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
}
