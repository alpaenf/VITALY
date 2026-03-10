<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing 'user' role records to 'kader' before changing enum
        DB::table('users')->where('role', 'user')->update(['role' => 'kader']);

        // Change enum values: ['admin', 'user'] -> ['admin', 'kader']
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kader') NOT NULL DEFAULT 'kader'");
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'kader')->update(['role' => 'user']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
