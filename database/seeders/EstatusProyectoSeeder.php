<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estatus_proyecto')->insert([
            ['nombre' => 'Activo', 'estatus' => true],
            ['nombre' => 'Inactivo', 'estatus' => true],
            ['nombre' => 'Completado', 'estatus' => true],
        ]);
    }
}
