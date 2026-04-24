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
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE konsultasis MODIFY COLUMN status ENUM('aktif', 'selesai', 'menunggu') NOT NULL DEFAULT 'aktif'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse if safely possible
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE konsultasis MODIFY COLUMN status ENUM('aktif', 'selesai') NOT NULL DEFAULT 'aktif'");
        }
    }
};
