<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_proyecto', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->onDelete('cascade');
        
            $table->foreignId('empleado_id')
                  ->constrained('empleados')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_proyecto');
    }
};