<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
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
     * Muestra la página de perfil del usuario actual
     */
    public function index()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // El usuario autenticado se recupera automáticamente con Auth::user()
        return view('admin.profile');
    }

    /**
     * Actualiza la contraseña del usuario actual
     */
    public function updatePassword(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

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

    /**
     * Actualiza la información del perfil del usuario actual
     */
}
