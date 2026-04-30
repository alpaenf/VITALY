<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            // Re-add oxygen_saturation for IoMT/smartwatch SpO2 readings
            if (!Schema::hasColumn('health_records', 'oxygen_saturation')) {
                $table->float('oxygen_saturation')->nullable()->after('heart_rate')->comment('Saturasi oksigen SpO2 (%)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $table->dropColumn('oxygen_saturation');
        });
    }
};
