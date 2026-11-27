<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisiciones', function (Blueprint $table) {
            $table->decimal('costo_servicio', 12, 2)->nullable()->after('tipo');
        });
    }

    public function down(): void
    {
        Schema::table('requisiciones', function (Blueprint $table) {
            $table->dropColumn('costo_servicio');
        });
    }
};
