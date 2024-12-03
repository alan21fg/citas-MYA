<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

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

        try {
            // Intento de autenticación y generación del token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Cargar usuario con relación 'rol'
        $usuario = User::with('rol')->find(Auth::id());

        // Respuesta con token y datos del usuario
        return response()->json([
            'usuario' => $usuario,
            'token' => $token,
        ], 200);
    }

    /**
     * Cierra la sesión del usuario autenticado.
     */
    public function logout()
    {
        try {
            // Invalidar el token
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not log out'], 500);
        }
    }
}
