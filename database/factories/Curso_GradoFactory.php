<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Grado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso_Grado>
 */
class Curso_GradoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_curso' => Curso::factory(),
            'id_grado' => Grado::factory(),
            'año_escolar' => fake()->randomElement(['2024', '2025', '2026', '2027', '2028']),
            'estado' => '1'
        ];
    }
}
