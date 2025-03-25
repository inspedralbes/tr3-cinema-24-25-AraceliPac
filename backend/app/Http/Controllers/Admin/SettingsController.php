<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
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
     * Muestra la página principal de configuración
     */
    public function index()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        return view('admin.settings.index');
    }
}
