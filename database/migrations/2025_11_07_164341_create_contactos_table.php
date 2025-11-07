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
    Schema::create('contactos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 100);
        $table->string('correo', 100);
        $table->string('telefono', 10);
        $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
