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
        Schema::create('facturas_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libro_ventas_periodo_id')->constrained('libro_ventas_periodos')->onDelete('cascade');
            $table->foreignId('contribuyente_id')->constrained('contribuyentes')->onDelete('cascade');

            // Datos básicos de la factura
            $table->date('fecha');
            $table->string('establecimiento'); // numérico pero como string por seguridad
            $table->string('tipo');
            $table->string('estado');
            $table->string('serie');
            $table->string('numero');

            // Datos del cliente
            $table->string('nit_cliente');
            $table->string('nombre_cliente');

            // Bases gravadas (requeridas)
            $table->decimal('base_gravada_bienes', 15, 2);
            $table->decimal('base_gravada_servicios', 15, 2)->nullable();

            // Bases exentas (opcionales)
            $table->decimal('base_exenta_bienes', 15, 2)->nullable();
            $table->decimal('base_exenta_servicios', 15, 2)->nullable();

            // IVA y totales
            $table->decimal('iva_debito_fiscal', 15, 2);
            $table->decimal('total_documento', 15, 2);

            // Campos opcionales
            $table->decimal('retencion', 15, 2)->nullable();
            $table->decimal('exencion', 15, 2)->nullable();

            // Auditoría
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['libro_ventas_periodo_id', 'fecha']);
            $table->index(['contribuyente_id', 'numero', 'serie']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_ventas');
    }
};
