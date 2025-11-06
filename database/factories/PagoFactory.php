<?php

namespace Database\Factories;
use App\Models\Deuda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_deuda' => Deuda::factory(),
            'fecha_pago' => fake()->dateTimeThisYear(),
            'monto' => fake()->randomFloat(2, 10, 1000),
            'observaciones' => fake()->optional()->sentence(),
            'estado' => '1'
        ];
    }
}
