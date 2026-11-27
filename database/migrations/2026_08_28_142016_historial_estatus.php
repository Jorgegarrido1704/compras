<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estatus', function (Blueprint $table) {
            $table->id();
            $table->morphs('trazable'); // requisicion u orden_compra
            $table->string('estatus_anterior')->nullable();
            $table->string('estatus_nuevo');
            $table->foreignId('user_id')->constrained('users');
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estatus');
    }
};
