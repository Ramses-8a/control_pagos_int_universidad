<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('servicios')->insert([
            [
                'nombre' => 'Desarrollo de E-commerce',
                'descripcion' => 'Creación de una tienda en línea personalizada.',
                'costo' => 25000.00,
                'precio' => 35000.00,
                'fk_tipo_servicio' => 1, // Asumiendo que el ID 1 existe en la tabla tipo_servicios (Desarrollo de Software)
                'estatus' => true,
            ],
            [
                'nombre' => 'Auditoría de Seguridad',
                'descripcion' => 'Análisis de vulnerabilidades en sistemas.',
                'costo' => 10000.00,
                'precio' => 15000.00,
                'fk_tipo_servicio' => 2, // Asumiendo que el ID 2 existe en la tabla tipo_servicios (Consultoría)
                'estatus' => true,
            ],
        ]);
    }
}