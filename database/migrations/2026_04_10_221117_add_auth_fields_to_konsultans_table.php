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
        Schema::table('konsultans', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('status');
            $table->string('password')->nullable()->after('username');
            $table->boolean('is_superadmin')->default(false)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konsultans', function (Blueprint $table) {
            $table->dropColumn(['username', 'password', 'is_superadmin']);
        });
    }
};
