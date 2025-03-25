<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Gestiona la petición de inicio de sesión administrativa
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Verificar si es administrador
            if (Auth::user()->role_id != 1) {
                Auth::logout();
                return back()->withErrors(['email' => 'No tens permisos d\'administrador']);
            }

            return redirect()->intended('/admin');
        }

        return back()->withErrors(['email' => 'Credencials invàlides']);
    }

    /**
     * Cierra la sesión de administración
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Muestra el dashboard de administración
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        return view('admin.home');
    }

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
}
