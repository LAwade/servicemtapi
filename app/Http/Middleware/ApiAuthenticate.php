<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PersonalToken;
use Auth;

class ApiAuthenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'message' => 'Token não fornecido.'
            ], 401);
        }

        // Verificar autenticação com o guard sanctum
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => 'Token expirado. Por favor, faça login novamente.'
            ], 401);
        }

        return $next($request);
    }
}
