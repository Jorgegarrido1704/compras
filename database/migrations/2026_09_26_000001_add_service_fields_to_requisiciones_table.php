<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisiciones', function (Blueprint $table) {
            $table->string('tipo')->default('compra')->after('descripcion');
            $table->date('fecha_pago_programada')->nullable()->after('estatus');
            $table->date('fecha_pago_real')->nullable()->after('fecha_pago_programada');
        });
    }

    public function down(): void
    {
        Schema::table('requisiciones', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'fecha_pago_programada', 'fecha_pago_real']);
        });
    }
};
