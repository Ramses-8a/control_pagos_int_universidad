<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PuestoSeeder::class,
            PeriodoPagoSeeder::class,
            EstatusTareasSeeder::class,
            EstatusProyectoSeeder::class,
            EmpleadoSeeder::class,
            ProyectoSeeder::class,
            TableroProyectoSeeder::class,
            TareaSeeder::class,
            PagosEmpleadosSeeder::class,
            TipoServicioSeeder::class,
            ServicioSeeder::class,
        ]);
    }
}