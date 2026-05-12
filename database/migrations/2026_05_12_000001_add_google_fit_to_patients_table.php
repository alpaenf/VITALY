<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('google_fit_access_token')->nullable()->after('device_id');
            $table->text('google_fit_refresh_token')->nullable()->after('google_fit_access_token');
            $table->timestamp('google_fit_token_expires_at')->nullable()->after('google_fit_refresh_token');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'google_fit_access_token',
                'google_fit_refresh_token',
                'google_fit_token_expires_at',
            ]);
        });
    }
};
