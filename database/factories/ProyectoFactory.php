<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\EstatusProyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    protected $model = Proyecto::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(),        // ← CORREGIDO
            'descripcion' => $this->faker->paragraph(),  // ← CORREGIDO
            'costo' => $this->faker->randomFloat(2, 1000, 50000),
            'precio' => $this->faker->randomFloat(2, 60000, 200000),
            'fecha_inicio' => now()->subDays(rand(1, 30)),
            'fecha_fin' => now()->addDays(rand(30, 90)),
            'estatus_proyecto_id' => EstatusProyecto::factory(),
        ];
    }
}
