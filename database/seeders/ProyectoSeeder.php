<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proyectos')->insert([
            [
                'nombre' => 'Desarrollo de App Móvil',
                'descripcion' => 'Creación de una aplicación móvil para gestión de tareas.',
                'costo' => 15000.00,
                'precio' => 20000.00,
                'estatus_proyecto_id' => 1, // Asumiendo que el ID 1 existe en la tabla estatus_proyecto (Activo)
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => '2024-06-30',
            ],
            [
                'nombre' => 'Diseño Web Corporativo',
                'descripcion' => 'Rediseño del sitio web de la empresa X.',
                'costo' => 8000.00,
                'precio' => 12000.00,
                'estatus_proyecto_id' => 3, // Asumiendo que el ID 3 existe en la tabla estatus_proyecto (Completado)
                'fecha_inicio' => '2024-09-01',
                'fecha_fin' => '2025-02-28',
            ],
            [
                'nombre' => 'Diseño Web Corporativo',
                'descripcion' => 'Rediseño del sitio web de la empresa X.',
                'costo' => 8000.00,
                'precio' => 12000.00,
                'estatus_proyecto_id' => 3, // Asumiendo que el ID 3 existe en la tabla estatus_proyecto (Completado)
                'fecha_inicio' => '2025-08-01',
                'fecha_fin' => '2026-01-31',
            ],
            [
                'nombre' => 'Diseño Web Corporativo',
                'descripcion' => 'Rediseño del sitio web de la empresa X.',
                'costo' => 8000.00,
                'precio' => 12000.00,
                'estatus_proyecto_id' => 3, // Asumiendo que el ID 3 existe en la tabla estatus_proyecto (Completado)
                'fecha_inicio' => '2025-09-01',
                'fecha_fin' => '2026-05-31',
            ],
        ]);
    }
}
