<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones');
            $table->string('folio')->unique();
            $table->foreignId('autorizado_por')->nullable()->constrained('users');
            $table->enum('estatus', [
                'pendiente_autorizacion',
                'autorizada',
                'rechazada',
                'pendiente_pago',
                'pagada',
                'cancelada',
            ])->default('pendiente_autorizacion');
            $table->text('motivo_rechazo')->nullable();
            $table->date('fecha_autorizacion')->nullable();
            $table->date('fecha_pago_programada')->nullable();
            $table->date('fecha_pago_real')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_compra');
    }
};
