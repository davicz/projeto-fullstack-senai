<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Lida com a tentativa de login de um usuário.
     */
    public function login(Request $request)
    {
        // 1. Valida se o email e a senha foram enviados
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Tenta autenticar o usuário com as credenciais fornecidas
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Se a autenticação falhar, lança um erro de validação
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // 3. Se a autenticação for bem-sucedida, pega o usuário
        $user = $request->user();
        
        // 4. Cria um token de API para este usuário
        $token = $user->createToken('auth-token')->plainTextToken;

        // 5. Retorna o token em uma resposta JSON
        return response()->json([
            'token' => $token,
        ]);
    }
}
