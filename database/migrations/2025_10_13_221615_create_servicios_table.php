<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable(); // Agregado
            $table->decimal('costo', 8, 2);
            $table->decimal('precio', 8, 2); // Agregado
            $table->boolean('estatus')->default(true);
            
            // Relación con tipo_servicios
            $table->foreignId('fk_tipo_servicio')
                  ->constrained('tipo_servicios')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};