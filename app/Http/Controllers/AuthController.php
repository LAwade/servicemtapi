<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
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
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Você desconectou com sucesso!']);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        $newToken = $user->createToken('auth-token', ['*'], now()->addMinutes(5))->plainTextToken;

        return response()->json(['token' => $newToken]);
    }
}
