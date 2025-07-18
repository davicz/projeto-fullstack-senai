<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ponto de entrada para todos os seeders da aplicação.
     */
    public function run(): void
    {
        // A ordem aqui é fundamental!
        // 1. Primeiro, chamamos o RoleSeeder para criar os perfis.
        // 2. Depois, chamamos o AdminUserSeeder que depende desses perfis.
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}