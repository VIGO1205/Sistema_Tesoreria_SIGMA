<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NivelEducativo>
 */
class NivelEducativoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_nivel' => fake()->randomElement(['PRIMARIA', 'SECUNDARIA']),
            'descripcion' => fake()->optional()->sentence()
        ];
    }
}
