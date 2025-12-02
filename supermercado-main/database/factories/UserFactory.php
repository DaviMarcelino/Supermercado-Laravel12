<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * A senha atual utilizada pela factory.
     */
    protected static ?string $password;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), // Nome falso
            'email' => fake()->unique()->safeEmail(), // E-mail único
            'email_verified_at' => now(), // E-mail verificado
            'password' => static::$password ??= Hash::make('password'), // Senha padrão
            'remember_token' => Str::random(10), // Token Lembre-me
        ];
    }

    /**
     * Indica que o e-mail do usuário deve estar NÃO verificado.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
