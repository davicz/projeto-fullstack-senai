<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Lida com a tentativa de autenticação de um usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validar os dados que chegam da requisição
        $credentials = $request->validate([
            // REGRA ATUALIZADA:
            // 'required': O campo é obrigatório.
            // 'regex:/.../': O campo deve corresponder ao formato de CPF especificado.
            'cpf' => [
                'required',
                'string',
                'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/'
            ],
            'password' => 'required|string|min:8',
        ], [
            // Mensagens de erro customizadas em português
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
        ]);

        // 2. Tentar autenticar o usuário
        if (!Auth::attempt($credentials)) {
            // Se a autenticação falhar, retorna um erro de não autorizado
            return response()->json(['message' => 'CPF ou senha inválidos.'], 401);
        }

        // 3. Se a autenticação for bem-sucedida, pegamos os dados do usuário logado
        $user = $request->user();

        // 4. Criamos um token de acesso para este usuário
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Retornamos uma resposta de sucesso completa para o frontend
        return response()->json([
            'message' => 'Login bem-sucedido!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }
}
