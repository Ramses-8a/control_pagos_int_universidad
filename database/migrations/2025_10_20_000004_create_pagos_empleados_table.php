<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos_empleados', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->text('descripcion');
            $table->unsignedBigInteger('fk_empleados');
            $table->unsignedBigInteger('fk_proyectos');
            $table->timestamps();
            
            $table->foreign('fk_empleados')->references('id')->on('empleados');
            $table->foreign('fk_proyectos')->references('id')->on('proyectos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_empleados');
    }
};