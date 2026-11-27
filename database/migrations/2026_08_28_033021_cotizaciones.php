<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicion_id')->constrained('requisiciones')->cascadeOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('creado_por')->constrained('users');
            $table->decimal('monto', 12, 2);
            $table->string('moneda')->default('MXN');
            $table->string('pdf_path')->nullable();
            $table->text('notas')->nullable();
            $table->boolean('seleccionada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
