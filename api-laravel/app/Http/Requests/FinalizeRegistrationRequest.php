<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password; // Importa a regra de senha

class FinalizeRegistrationRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     * Como é um cadastro público (via link), a autorização é sempre verdadeira.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação que se aplicam à requisição.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Validações para os campos do novo colaborador
            'name'    => ['required', 'string', 'max:100'],
            
            // A CORREÇÃO ESTÁ AQUI:
            // O erro 'column " cpf" does not exist' é causado por um espaço
            // depois da vírgula na regra 'unique'. A forma correta é 'unique:users,cpf'.
            'cpf'     => ['required', 'string', 'size:11', 'unique:users,cpf'],

            'phone'   => ['nullable', 'string', 'size:11'],
            'cep'     => ['nullable', 'string', 'size:8'],
            'token'   => ['required', 'string', 'exists:invitations,token'],

            // Validações para a senha.
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }
}
