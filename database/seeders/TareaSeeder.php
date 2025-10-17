<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tareas')->insert([
            [
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
                'titulo' => 'Definir requisitos de la app',
                'notas' => 'Reunión con el cliente para detallar funcionalidades.',
                'fecha_inicio' => '2024-01-16',
                'fecha_fin' => '2024-01-20',
                'fk_estatus_tarea' => 2, // Asumiendo que el ID 2 existe en la tabla estatus_tareas (En Progreso)
                'fk_empleados' => 1, // Asumiendo que el ID 1 existe en la tabla empleados
            ],
            [
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
                'titulo' => 'Diseñar interfaz de usuario',
                'notas' => 'Crear wireframes y mockups para la app.',
                'fecha_inicio' => '2024-01-21',
                'fecha_fin' => '2024-02-10',
                'fk_estatus_tarea' => 1, // Asumiendo que el ID 1 existe en la tabla estatus_tareas (Pendiente)
                'fk_empleados' => 2, // Asumiendo que el ID 2 existe en la tabla empleados
            ],
        ]);
    }
}
