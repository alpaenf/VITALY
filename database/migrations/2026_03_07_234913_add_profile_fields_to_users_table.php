<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // These columns were already added in 2025_01_01_000003_add_role_to_users_table.php
        // Profile fields have been moved to the patients table in the new architecture
    }

    public function down(): void
    {
        //
    }
};
