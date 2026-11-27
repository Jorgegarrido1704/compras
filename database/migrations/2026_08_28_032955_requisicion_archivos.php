<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisicion_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicion_id')->constrained('requisiciones')->cascadeOnDelete();
            $table->string('nombre_original');
            $table->string('ruta');
            $table->string('tipo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisicion_archivos');
    }
};
