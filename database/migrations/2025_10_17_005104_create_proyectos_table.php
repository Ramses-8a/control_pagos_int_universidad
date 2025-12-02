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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id(); 
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->foreignId('estatus_proyecto_id') 
                  ->default(1) 
                  ->constrained('estatus_proyecto') 
                  ->onDelete('restrict'); 
            

            $table->date('fecha_inicio'); 
            $table->date('fecha_fin')->nullable(); 
            $table->timestamps(); 
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};