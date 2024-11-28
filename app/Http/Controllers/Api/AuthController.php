<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Maneja el inicio de sesión del usuario.
     */
    public function login(Request $request)
    {
        // Validación de las credenciales
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Intento de autenticación
        if (Auth::guard('web')->attempt($credentials)) {
            // Regeneración de la sesión para evitar fijación de sesión
            $request->session()->regenerate();

            // Cargar usuario con relación 'rol'
            $usuario = User::with('rol')->find(Auth::id());

            // Respuesta de éxito con datos del usuario autenticado
            return response()->json([
                'usuario' => $usuario,
            ], 200);
        }

        // Respuesta en caso de fallo de autenticación
        return response()->json(['error' => 'Unauthorized'], 401);
    }



    /**
     * Cierra la sesión del usuario autenticado.
     */
    public function logout(Request $request)
    {
        // Invalida la sesión del usuario actual
        Auth::logout();

        // Limpia la sesión y regenera el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Respuesta de éxito al cerrar sesión
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
