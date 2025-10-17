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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_proyectos');
            $table->string('titulo');
            $table->text('notas');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unsignedBigInteger('fk_estatus_tarea');
            $table->unsignedBigInteger('fk_empleados');
            $table->timestamps();
            
            $table->foreign('fk_proyectos')->references('id')->on('proyectos');
            $table->foreign('fk_estatus_tarea')->references('id')->on('estatus_tareas');
            $table->foreign('fk_empleados')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};