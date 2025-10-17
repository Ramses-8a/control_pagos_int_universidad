<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_servicios')->insert([
            ['nombre' => 'Desarrollo de Software', 'estatus' => true],
            ['nombre' => 'Consultoría', 'estatus' => true],
            ['nombre' => 'Diseño Gráfico', 'estatus' => true],
        ]);
    }
}
