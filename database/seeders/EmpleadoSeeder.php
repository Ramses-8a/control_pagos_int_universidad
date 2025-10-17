<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empleados')->insert([
            [
                'nombre' => 'Juan',
                'apaterno' => 'Perez',
                'amaterno' => 'Gomez',
                'correo' => 'juan.perez@example.com',
                'fk_puestos' => 1, // Asumiendo que el ID 1 existe en la tabla puestos
                'fk_periodo_pago' => 1, // Asumiendo que el ID 1 existe en la tabla periodo_pago
                'estatus' => true,
            ],
            [
                'nombre' => 'Maria',
                'apaterno' => 'Lopez',
                'amaterno' => 'Diaz',
                'correo' => 'maria.lopez@example.com',
                'fk_puestos' => 2, // Asumiendo que el ID 2 existe en la tabla puestos
                'fk_periodo_pago' => 2, // Asumiendo que el ID 2 existe en la tabla periodo_pago
                'estatus' => true,
            ],
        ]);
    }
}
