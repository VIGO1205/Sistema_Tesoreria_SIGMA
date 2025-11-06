<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Familiar>
 */
class FamiliarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $firstName = fake()->firstName();
        $fatherLastName = fake()->lastName();
        $customUsername = $firstName . "." . $fatherLastName;
        return [
            'dni' => fake()->numberBetween(10000000, 99999999),
            'apellido_paterno' => $fatherLastName,
            'apellido_materno' => fake()->lastName(),
            'primer_nombre' => $firstName,
            'otros_nombres' => fake()->randomElement([fake()->name(), fake()->name() . " " . fake()->name()]),
            'numero_contacto' => fake()->phoneNumber(),
            'correo_electronico' => fake()->unique()->safeEmail(),
            'id_usuario' => User::factory(['tipo' => 'Familiar', 'username' => $customUsername]),
            
        ];
    }
}
