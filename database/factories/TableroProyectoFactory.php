<?php

namespace Database\Factories;

use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableroProyectoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3), 
            'descripcion' => $this->faker->sentence(),   
            'estatus' => $this->faker->boolean(),        
            'fk_proyecto' => Proyecto::factory(),
        ];
    }
}
