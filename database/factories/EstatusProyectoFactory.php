<?php

namespace Database\Factories;

use App\Models\EstatusProyecto; 
use Illuminate\Database\Eloquent\Factories\Factory;

class EstatusProyectoFactory extends Factory
{
    /**
     * El modelo asociado a la factory.
     *
     * @var string
     */
    protected $model = EstatusProyecto::class; 

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           
            'nombre' => $this->faker->word,
            'estatus' => 1, 
        ];
    }
}