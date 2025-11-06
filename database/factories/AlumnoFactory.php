<?php

namespace Database\Factories;

use App\Models\Matricula;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumno>
 */
class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $fatherLastName = fake()->lastName();
        $apellidoMaterno = fake()->lastName();

        return [
            'codigo_educando' => fake()->numberBetween(100000, 999999),
            'codigo_modular' => fake()->numberBetween(100000, 999999),
            'año_ingreso' => fake()->year(),
            'dni' => fake()->numberBetween(10000000, 99999999),
            'apellido_paterno' => $fatherLastName,
            'apellido_materno' => $apellidoMaterno,
            'primer_nombre' => fake()->firstName(),
            'otros_nombres' => fake()->name(),
            'sexo' => fake()->randomElement(['M', 'F']),
            'fecha_nacimiento' => fake()->date(),
            'pais' => 'Peru',
            'departamento' => fake()->randomElement(['LIMA', 'LA LIBERTAD', 'CAJAMARCA', 'PIURA']),
            'provincia' => fake()->city(),
            'distrito' => fake()->city(),
            'lengua_materna' => fake()->randomElement(['CASTELLANO', 'QUECHUA', 'AIMARA', 'OTRO']),
            'estado_civil' => fake()->randomElement(['S', 'C', 'V', 'D']),
            'religion' => fake()->optional()->word(),
            'fecha_bautizo' => fake()->optional()->date(),
            'parroquia_bautizo' => fake()->optional()->sentence(3),
            'colegio_procedencia' => fake()->optional()->company(),
            'direccion' => fake()->address(),
            'telefono' => fake()->optional()->phoneNumber(),
            'medio_transporte' => fake()->randomElement(['A PIE', 'COMBI', 'MICRO', 'MOTO', 'BICICLETA', 'AUTO', 'TAXI']),
            'tiempo_demora' => fake()->optional()->numberBetween(5, 120),
            'material_vivienda' => fake()->randomElement(['LADRILLO/CEMENTO','ADOBE','OTRO']),
            'energia_electrica' => fake()->randomElement(['INSTALACION DOMICILIARIA','MEDIDOR COMUNITARIO','GENERADOR PROPIO','OTRO']),
            'agua_potable' => fake()->optional()->randomElement(['INSTALACION DOMICILIARIA', 'INSTALACION COMPARTIDA','CAMION SISTERNA','POZO']),
            'desague' => fake()->optional()->randomElement(['INSTALACION DOMICILIARIA', 'POZO SÉPTICO','LETRINA','OTRO']),
            'ss_hh' => fake()->optional()->randomElement(['INODORO CON AGUA CORRIENTE', 'INODORO SIN AGUA','LETRINA','OTRO']),
            'num_habitaciones' => fake()->optional()->numberBetween(1, 10),
            'num_habitantes' => fake()->optional()->numberBetween(1, 10),
            'situacion_vivienda' => fake()->randomElement(['PROPIA', 'ALQUILADA','CEDIDA','PROMOVIDO', 'OTRO']),
            'escala' => fake()->optional()->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
