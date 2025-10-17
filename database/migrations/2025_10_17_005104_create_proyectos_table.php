<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
  
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id(); 
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('costo', 10, 2);
            $table->decimal('precio', 10, 2);
            $table->unsignedBigInteger('fk_estatus_proyecto');
            $table->foreign('fk_estatus_proyecto')->references('id')->on('estatus_proyecto');
            $table->date('fecha_inicio'); 
            $table->date('fecha_fin')->nullable(); 
            $table->timestamps(); 
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
