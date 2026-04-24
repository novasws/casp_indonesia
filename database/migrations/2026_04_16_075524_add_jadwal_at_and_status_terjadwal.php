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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dateTime('jadwal_at')->nullable()->after('status');
        });

        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dateTime('jadwal_at')->nullable()->after('mulai_at');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE konsultasis MODIFY COLUMN status ENUM('aktif', 'selesai', 'menunggu', 'terjadwal') NOT NULL DEFAULT 'aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn('jadwal_at');
        });

        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn('jadwal_at');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE konsultasis MODIFY COLUMN status ENUM('aktif', 'selesai', 'menunggu') NOT NULL DEFAULT 'aktif'");
    }
};
