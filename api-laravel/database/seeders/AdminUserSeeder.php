<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importa o Model User
use App\Models\Role; // Importa o Model Role

class AdminUserSeeder extends Seeder
{
    /**
     * Cria um usuário administrador e o associa ao seu perfil.
     */
    public function run(): void
    {
        // 1. Busca o perfil de 'Administrador' que foi criado pelo RoleSeeder.
        $adminRole = Role::where('slug', 'admin')->first();

        // Se o perfil não for encontrado, o seeder para aqui para evitar erros.
        if (!$adminRole) {
            $this->command->error('Perfil "admin" não encontrado. Rode o RoleSeeder primeiro.');
            return;
        }

        // 2. Cria o usuário administrador se ele ainda não existir no banco.
        $adminUser = User::firstOrCreate(
            // Chave para verificar se o usuário já existe:
            ['email' => 'admin@techsolutions.com'],
            // Dados para criar o usuário caso ele não exista:
            [
                'name' => 'Admin do Sistema',
                'cpf' => '11122233344', // Lembre-se: 11 dígitos, sem máscara
                'password' => 'Password@123', // O Model User já faz o hash automaticamente
            ]
        );

        // 3. Associa o perfil de 'Administrador' a este usuário.
        // O método 'syncWithoutDetaching' garante que a relação seja criada
        // sem remover outros perfis que o usuário possa ter.
        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);

        // Exibe uma mensagem de sucesso no console
        $this->command->info('Usuário administrador criado e associado com sucesso!');
    }
}