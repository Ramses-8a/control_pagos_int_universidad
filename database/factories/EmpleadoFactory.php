<?php

namespace Database\Factories;

use App\Models\PeriodoPago; // <-- Importante
use App\Models\Puesto;      // <-- Importante
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Coincide con tu $fillable
            'nombre' => $this->faker->firstName(),
            'apaterno' => $this->faker->lastName(),
            'amaterno' => $this->faker->lastName(),
            'correo' => $this->faker->unique()->safeEmail(),
            'estatus' => '1', // '1' para activo, basado en tu scopeActivos

            // Asigna las llaves foráneas llamando a sus factories
            'fk_puestos' => Puesto::factory(),
            'fk_periodo_pago' => PeriodoPago::factory(),
        ];
    }
}