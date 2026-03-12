<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $table->dropColumn('oxygen_saturation');
            $table->integer('respiratory_rate')->nullable()->after('temperature')->comment('Laju nafas / Respiratory Rate (x/menit)');
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $table->dropColumn('respiratory_rate');
            $table->integer('oxygen_saturation')->nullable()->after('temperature')->comment('Saturasi oksigen SpO2 (%)');
        });
    }
};
