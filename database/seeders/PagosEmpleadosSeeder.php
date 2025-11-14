<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosEmpleadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pagos_empleados')->insert([
            [
                'monto' => 1200.50,
                'fecha_pago' => '2025-08-15',
                'descripcion' => 'Pago quincenal de agosto',
                'fk_empleados' => 1, // Asumiendo que el ID 1 existe en la tabla empleados
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
            ],
            [
                'monto' => 1500.00,
                'fecha_pago' => '2025-08-30',
                'descripcion' => 'Pago quincenal de agosto',
                'fk_empleados' => 2, // Asumiendo que el ID 2 existe en la tabla empleados
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
            ],
            [
                'monto' => 1300.00,
                'fecha_pago' => '2025-09-15',
                'descripcion' => 'Pago quincenal de septiembre',
                'fk_empleados' => 1, // Asumiendo que el ID 1 existe en la tabla empleados
                'fk_proyectos' => 2, // Asumiendo que el ID 2 existe en la tabla proyectos
            ],
            [
                'monto' => 1500.00,
                'fecha_pago' => '2025-02-15',
                'descripcion' => 'Pago quincenal de febrero',
                'fk_empleados' => 2, // Asumiendo que el ID 2 existe en la tabla empleados
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
            ],
            [
                'monto' => 1500.00,
                'fecha_pago' => '2025-10-15',
                'descripcion' => 'Pago quincenal de octubre',
                'fk_empleados' => 1, // Asumiendo que el ID 1 existe en la tabla empleados
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
            ],
            [
                'monto' => 1500.00,
                'fecha_pago' => '2025-10-30',
                'descripcion' => 'Pago quincenal de octubre',
                'fk_empleados' => 2, // Asumiendo que el ID 2 existe en la tabla empleados
                'fk_proyectos' => 1, // Asumiendo que el ID 1 existe en la tabla proyectos
            ],
            [
                'monto' => 1300.00,
                'fecha_pago' => '2025-11-15',
                'descripcion' => 'Pago quincenal de noviembre',
                'fk_empleados' => 1, // Asumiendo que el ID 1 existe en la tabla empleados
                'fk_proyectos' => 2, // Asumiendo que el ID 2 existe en la tabla proyectos
            ],
        ]);
    }
}