<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusTareasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estatus_tareas')->insert([
            ['nombre' => 'Pendiente', 'estatus' => true],
            ['nombre' => 'En Progreso', 'estatus' => true],
            ['nombre' => 'Completada', 'estatus' => true],
        ]);
    }
}
