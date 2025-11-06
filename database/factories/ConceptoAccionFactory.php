<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConceptoAccion>
 */
class ConceptoAccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descripcion' => fake()->randomElement(['Acción 1', 'Acción 2', 'Acción 3', 'Acción 4']),
            'estado' => '1',
        ];
    }
}
