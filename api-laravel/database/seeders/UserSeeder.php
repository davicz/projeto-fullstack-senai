<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Cria um usuário administrador e o associa ao seu perfil.
     */
    public function run(): void
    {
        // 1. Busca o perfil de Administrador que foi criado pelo RoleSeeder
        $adminRole = Role::where('slug', 'admin')->first();

        // 2. Cria o usuário administrador se ele não existir
        //    O método 'firstOrCreate' é ótimo para isso.
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@techsolutions.com'], // Chave para verificar se já existe
            [
                'name' => 'Admin do Sistema',
                'cpf' => '11122233344', 
                'password' => 'Password@123', 
            ]
        );

        // 3. Atribui o perfil de admin ao usuário, se o perfil existir.
        //    O método 'syncWithoutDetaching' anexa o perfil sem remover os que já existem.
        if ($adminRole) {
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}
