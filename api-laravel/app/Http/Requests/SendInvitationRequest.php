<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     * Aqui você pode adicionar a lógica para permitir que apenas
     * 'Administrador' ou 'Gente e Cultura' possam convidar.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Por enquanto, vamos permitir. No futuro, você pode usar:
        // return $this->user() && ($this->user()->hasRole('admin') || $this->user()->hasRole('hr'));
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
            // Validações:
            // 1. 'required': O campo email é obrigatório.
            // 2. 'email': Deve ser um formato de e-mail válido.
            // 3. 'unique:users,email': O e-mail não pode já existir na tabela de usuários.
            // 4. 'unique:invitations...': Não pode haver outro convite EM ABERTO para o mesmo e-mail.
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'unique:invitations,email,NULL,id,status,Em Aberto'
            ],
        ];
    }

    /**
     * Customiza as mensagens de erro para as regras de validação.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já pertence a um usuário ou já possui um convite ativo.',
        ];
    }
}
