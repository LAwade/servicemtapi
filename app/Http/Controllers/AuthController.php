<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Os dados fornecidos são inválidos'], 403);
            }
    
            $token = $user->createToken('auth-token', ['*'], now()->addMinutes(5))->plainTextToken;
            return response()->json(['token' => $token], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao autenticar usuário', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao gerar token'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao gerar token', 'error' => $e->getMessage()], 500);
        } 
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Você desconectou com sucesso!'], 200);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        $newToken = $user->createToken('auth-token', ['*'], now()->addMinutes(5))->plainTextToken;

        return response()->json(['token' => $newToken], 200);
    }
}
