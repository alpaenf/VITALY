<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('patient_id')->nullable()->after('id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('recorded_by')->nullable()->after('patient_id')->constrained('users')->onDelete('set null');
        });

        // Drop old foreign key and column
        Schema::table('health_records', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->dropForeign(['recorded_by']);
            $table->dropColumn('recorded_by');
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');
        });
    }
};
