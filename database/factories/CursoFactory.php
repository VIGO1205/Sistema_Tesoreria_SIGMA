<?php

namespace Database\Factories;

use App\Models\NivelEducativo;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_nivel' => NivelEducativo::factory(),    
            'codigo_curso' => fake()->randomElement(['MA', 'CI', 'LE', 'HI', 'GE', 'IN', 'EF']),
            'nombre_curso' => fake()->randomElement(['MATEMÁTICA', 'CIENCIAS', 'LENGUAJE', 'HISTORIA', 'GEOGRAFÍA', 'INGLÉS', 'EDUCACIÓN FÍSICA']),
        ];
    }
}
