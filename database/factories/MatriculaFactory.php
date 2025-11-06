<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Seccion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matricula>
 */
class MatriculaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seccion = Seccion::factory()->create();
        return [
            'id_alumno' => Alumno::factory(),
            'año_escolar' => fake()->randomElement(['2024', '2025', '2026', '2027', '2028']),
            'fecha_matricula' => fake()->dateTimeBetween('-1 year', 'now'),
            'escala' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
            'observaciones' => fake()->sentence(),
            'id_grado' => $seccion->id_grado,
            'nombreSeccion' => $seccion->nombreSeccion,
        ];
    }
}
