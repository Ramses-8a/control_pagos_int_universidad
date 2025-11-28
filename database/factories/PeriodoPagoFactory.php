<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeriodoPago>
 */
class PeriodoPagoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Coincide con tu $fillable ['nombre', 'estatus']
        return [
            'nombre' => $this->faker->randomElement(['Quincenal', 'Semanal', 'Mensual']),
            'estatus' => '1', // Basado en tu scopeActivos
        ];
    }
}