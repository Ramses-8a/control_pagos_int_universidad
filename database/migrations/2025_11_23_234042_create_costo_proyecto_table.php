<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('costo_proyecto', function (Blueprint $table) {
            $table->id();
            
            
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->onDelete('cascade'); 
            
            $table->string('concepto'); 
            $table->decimal('monto', 10, 2);
            
            $table->timestamps();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('costo_proyecto');
    }
};
