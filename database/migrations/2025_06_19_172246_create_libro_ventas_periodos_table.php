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
        Schema::create('libro_ventas_periodos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribuyente_id')->constrained('contribuyentes')->onDelete('cascade');
            $table->integer('año');
            $table->integer('mes'); // 1-12
            $table->enum('estado', ['abierto', 'cerrado'])->default('abierto');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Un contribuyente no puede tener el mismo año/mes duplicado
            $table->unique(['contribuyente_id', 'año', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_ventas_periodos');
    }
};
