<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\EstatusProyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    /**
     * El modelo asociado a la factory.
     *
     * @var string
     */
    protected $model = Proyecto::class; 

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->catchPhrase,
            'descripcion' => $this->faker->paragraph,
            'costo' => $this->faker->randomFloat(2, 1000, 50000),
            'precio' => $this->faker->randomFloat(2, 60000, 200000),
            'fecha_inicio' => now()->subDays(rand(1, 30)),
            'fecha_fin' => now()->addDays(rand(30, 90)),
            'estatus_proyecto_id' => EstatusProyecto::factory(),
        ];
    }
}