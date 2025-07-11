<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Popula a tabela de perfis (roles).
     */
    public function run(): void
    {
        // Usamos firstOrCreate para evitar criar perfis duplicados
        // se rodarmos o seeder várias vezes.
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrador']);
        Role::firstOrCreate(['slug' => 'hr'], ['name' => 'Gente e Cultura']);
        Role::firstOrCreate(['slug' => 'colaborador-comum'], ['name' => 'Colaborador Comum']);
    }
}
