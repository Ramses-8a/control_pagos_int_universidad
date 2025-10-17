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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apaterno'); 
            $table->string('amaterno'); 
            $table->string('correo')->unique();
            $table->unsignedBigInteger('fk_puestos');
            $table->unsignedBigInteger('fk_periodo_pago');
            $table->boolean('estatus')->default(true);
            $table->timestamps();

            $table->foreign('fk_puestos')->references('id')->on('puestos');
            $table->foreign('fk_periodo_pago')->references('id')->on('periodo_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};