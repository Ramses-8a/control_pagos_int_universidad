<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('puestos')->insert([
            ['nombre' => 'Gerente de Proyecto', 'estatus' => true],
            ['nombre' => 'Desarrollador Senior', 'estatus' => true],
            ['nombre' => 'Diseñador UX/UI', 'estatus' => true],
        ]);
    }
}
