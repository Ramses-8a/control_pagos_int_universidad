<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagos_empleados', function (Blueprint $table) {
            // Primero hacer que la columna pueda ser nula temporalmente
            $table->date('fecha_pago')->nullable()->change();
        });

        
        DB::table('pagos_empleados')->whereNull('fecha_pago')->update([
            'fecha_pago' => now()
        ]);

        
        Schema::table('pagos_empleados', function (Blueprint $table) {
            $table->date('fecha_pago')->default(DB::raw('CURRENT_DATE'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_empleados', function (Blueprint $table) {
            $table->date('fecha_pago')->nullable()->change();
        });
    }
};