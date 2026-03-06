<?php

namespace Database\Seeders;

use App\Models\Clientes;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar usuário principal
        User::factory()->create([
            'us_nome' => 'Administrador',
            'us_email' => 'adm@adm.com',
        ]);

        // 1. Criar usuários para teste
        Clientes::factory(10)->create();
    }
}
