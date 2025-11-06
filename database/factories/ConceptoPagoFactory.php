<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConceptoPago>
 */
class ConceptoPagoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descripcion' => fake()-> randomElement([
                'RATIFICACION DE MATRICULA', 
                'PENSION MARZO', 
                'PENSION ABRIL', 
                'PENSION MAYO', 
                'PENSION JUNIO', 
                'PENSION JULIO',
                'PENSION AGOSTO',
                'PENSION SEPTIEMBRE',
                'PENSION OCTUBRE',
                'PENSION NOVIEMBRE',
                'PENSION DICIEMBRE',
            ]),

            'escala' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
            'monto' => fake()->randomFloat(2, 10, 1000), 
            'estado' => '1'
        ];
    }
}
