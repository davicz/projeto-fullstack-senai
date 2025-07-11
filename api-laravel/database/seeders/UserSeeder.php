<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Importa o model User
use Illuminate\Support\Facades\Hash; // Importa o Hash para criptografar a senha

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin do Sistema',
            'email' => 'admin@techsolutions.com',
            'cpf' => '000.000.000-00', // CPF para o login de teste
            'password' => Hash::make('Password@123'), // Senha de teste. O Hash::make criptografa.
            'role' => 'Administrador', // O perfil do nosso usuário de teste
        ]);
    }
}