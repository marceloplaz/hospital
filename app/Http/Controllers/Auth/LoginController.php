<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // <<--- ESTA LÍNEA ES IMPORTANTE
use Illuminate\Support\Facades\Auth; // Opcional pero recomendada

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Donde redirigir a los usuarios tras el login.
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // Eliminamos el middleware 'auth' de aquí para evitar conflictos 
        // ya que lo manejaremos desde el archivo de rutas (web.php)
    }

    /**
     * Sobrescribimos el método logout para ir directo al login
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Limpia cualquier rastro de caché para que no puedan volver atrás
        return redirect('/login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT',
        ]);
    }
}