<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\EstatusTarea;
use App\Models\Proyecto;
use App\Models\TableroProyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarea>
 */
class TareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Coincide con tu $fillable
        return [
            'titulo' => $this->faker->sentence(4), // Genera un título falso
            'notas' => $this->faker->paragraph(),
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'fecha_fin' => $this->faker->dateTimeBetween('+1 week', '+3 weeks'),

            // Asigna todas las llaves foráneas llamando a sus factories
            'fk_proyectos' => Proyecto::factory(),
            'fk_tablero_proyecto' => TableroProyecto::factory(),
            'fk_empleados' => Empleado::factory(),
            'fk_estatus_tarea' => EstatusTarea::factory(),
        ];
    }
}