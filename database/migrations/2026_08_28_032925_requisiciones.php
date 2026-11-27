<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisiciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitante_id')->constrained('users');
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('estatus', [
                'solicitada',
                'en_cotizacion',
                'pendiente_autorizacion',
                'autorizada',
                'rechazada',
                'pendiente_pago',
                'pagada',
                'cancelada',
            ])->default('solicitada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisiciones');
    }
};
