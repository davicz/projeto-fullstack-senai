<?php

namespace App\Services;

use App\Models\User;
use App\Models\Invitation;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException; // Usado para lançar erros de validação

class UserService
{
    /**
     * Cria um novo usuário a partir de um convite válido.
     * Este método contém toda a orquestração da lógica de negócio.
     *
     * @param array $data Os dados já validados vindos da FinalizeRegistrationRequest.
     * @return User O objeto do usuário recém-criado.
     * @throws \Illuminate\Validation\ValidationException Se o convite for inválido.
     * @throws \Exception Para outros erros inesperados.
     */
    public function createUserFromInvitation(array $data): User
    {
        // Passo 1: Encontrar o convite pelo token que veio do formulário.
        $invitation = Invitation::where('token', $data['token'])->first();

        // Passo 2: Validar o convite.
        if (!$invitation || $invitation->status !== 'Em Aberto' || now()->isAfter($invitation->expires_at)) {
            throw ValidationException::withMessages([
                'token' => 'Este token de convite é inválido, expirado ou já foi utilizado.'
            ]);
        }

        // Passo 3: Usar uma transação de banco de dados.
        return DB::transaction(function () use ($data, $invitation) {
            
            // 3a. Cria o novo usuário no banco de dados.
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $invitation->email, // Usamos o e-mail do convite para segurança!
                'cpf'      => $data['cpf'],
                'password' => $data['password'], // O Model User já faz o hash automaticamente
                'phone'    => $data['phone'] ?? null,
                'cep'      => $data['cep'] ?? null,
                // ... outros campos de endereço se houver
            ]);

            // 3b. Encontra o perfil (Role) padrão para novos colaboradores.
            $defaultRole = Role::where('slug', 'colaborador-comum')->firstOrFail();

            // 3c. Associa o novo usuário ao perfil padrão.
            $user->roles()->attach($defaultRole->id);

            // 3d. Atualiza o status do convite para 'Finalizado'.
            $invitation->update(['status' => 'Finalizado']);

            // Opcional: Disparar um evento para outras ações, como enviar e-mail de boas-vindas.
            // event(new UserWasCreated($user));

            // 3e. Retorna o objeto do usuário recém-criado.
            return $user;

        }); // Fim da transação do DB
        
    } // <--- ESTA É A CHAVE '}' QUE FALTAVA PARA FECHAR O MÉTODO createUserFromInvitation

    /**
     * Encontra um usuário específico pelo seu ID.
     *
     * @param int $id O ID do usuário a ser encontrado.
     * @return \App\Models\User
     */
    public function findUserById(int $id)
    {
        // Lógica:
        // 1. Usamos 'with('roles')' para já carregar os perfis do usuário.
        // 2. Usamos 'findOrFail($id)' que é um atalho útil:
        //    - Se encontrar o usuário, ele o retorna.
        //    - Se NÃO encontrar, ele automaticamente lança um erro 404 Not Found.
        return \App\Models\User::with('roles')->findOrFail($id);
    }
    
} // <--- FIM DA CLASSE UserService. A chave extra que estava aqui foi removida.