<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableroProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tablero_proyecto')->insert([
            ['nombre' => 'Tablero de Diseño', 'descripcion' => 'Tareas relacionadas con el diseño UI/UX', 'fk_proyecto' => 1, 'estatus' => true],
            ['nombre' => 'Tablero de Desarrollo', 'descripcion' => 'Tareas de implementación de funcionalidades', 'fk_proyecto' => 2, 'estatus' => true],
            ['nombre' => 'Tablero de Pruebas', 'descripcion' => 'Tareas de testing y control de calidad', 'fk_proyecto' => 3, 'estatus' => true],
        ]);
    }
}
