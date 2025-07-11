<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Esta linha diz ao Laravel para executar o nosso UserSeeder
        $this->call([
            UserSeeder::class,
        ]);
    }
}