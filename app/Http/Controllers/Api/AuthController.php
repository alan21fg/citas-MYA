<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;
            return response()->json([
                'token' => $token,
                'user' => $user
            ], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Cierra la sesión del usuario autenticado
     */
    public function logout(Request $request)
    {
        // Revoca el token de acceso actual
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
