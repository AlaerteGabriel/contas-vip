<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ClientesFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cl_nome' => fake('pt_BR')->name(),
            'cl_username' => fake('pt_BR')->userName(),
            'cl_email' => fake('pt_BR')->unique()->safeEmail(),
            'cl_email_envio' => fake('pt_BR')->unique()->safeEmail(),
            'cl_cel' => fake('pt_BR')->phoneNumber(),
            'cl_banido' => rand(0,1),
        ];
    }
}
