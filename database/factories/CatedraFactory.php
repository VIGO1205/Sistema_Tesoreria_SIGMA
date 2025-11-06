<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Grado;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seccion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Catedra>
 */
class CatedraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seccion = Seccion::inRandomOrder()->first();
        return [
            'id_personal' => Personal::factory(),
            'id_curso' => Curso::factory(),
            'año_escolar' => fake()->randomElement(['2024', '2025', '2026', '2027', '2028']),
            'id_grado' => $seccion->id_grado,
            'secciones_nombreSeccion' => $seccion->nombreSeccion,
        ];
    }
}
