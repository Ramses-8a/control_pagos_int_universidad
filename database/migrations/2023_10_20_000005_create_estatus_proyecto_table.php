<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            
            $table->foreignId('estatus_proyecto_id')
                  ->default(1) 
                  ->constrained('estatus_proyecto') 
                  ->onDelete('restrict'); 
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropForeign(['estatus_proyecto_id']);
            $table->dropColumn('estatus_proyecto_id');
      
        });
    }
};