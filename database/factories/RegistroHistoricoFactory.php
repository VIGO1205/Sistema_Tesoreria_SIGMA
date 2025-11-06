<?php

namespace Database\Factories;

use App\Models\ConceptoAccion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroHistorico>
 */
class RegistroHistoricoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_autor' => User::factory(),
            'id_concepto_accion' => ConceptoAccion::factory(),
            'id_usuario_afectado' => User::factory(),
            'fecha_accion' => fake()->dateTimeBetween('-1 year', 'now'),
            'observacion' => fake()->sentence(),
            'estado' => '1'
        ];
    }
}
