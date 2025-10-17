<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periodo_pago')->insert([
            ['nombre' => 'Semanal', 'estatus' => true],
            ['nombre' => 'Quincenal', 'estatus' => true],
            ['nombre' => 'Mensual', 'estatus' => true],
        ]);
    }
}
