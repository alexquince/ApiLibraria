<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Error en las credenciales'], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Credenciales erróneas'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Login exitoso', 'token' => $token, 'user' => $user], 200);
        } catch (\Exception $e) {
            Log::error('Error en login: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el servidor'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado'], 401);
            }

            $user->tokens()->delete();

            return response()->json(['message' => 'Logout exitoso'], 200);
        } catch (\Exception $e) {
            Log::error('Error en logout: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el servidor'], 500);
        }
    }

    public function validateToken(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'Token no proporcionado'], 401);
            }

            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken || !$accessToken->tokenable) {
                return response()->json(['message' => 'Token no válido'], 401);
            }

            return response()->json(['userId' => $accessToken->tokenable->id], 200);
        } catch (\Exception $e) {
            Log::error('Error en validateToken: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el servidor'], 500);
        }
    }
}
