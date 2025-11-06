<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Familiar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComposicionFamiliar>
 */
class ComposicionFamiliarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_alumno' => Alumno::factory(),      
            'id_familiar' => Familiar::factory(),
            'parentesco' => $this->faker->randomElement(['Padre', 'Madre', 'Hermano', 'Tío', 'Abuelo', 'Otro']),
        ];
    }
}
