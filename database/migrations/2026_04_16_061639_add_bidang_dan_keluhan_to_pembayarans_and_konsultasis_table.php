<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('bidang_hukum')->nullable()->after('hp_klien');
            $table->text('deskripsi_keluhan')->nullable()->after('bidang_hukum');
        });

        Schema::table('konsultasis', function (Blueprint $table) {
            $table->string('bidang_hukum')->nullable()->after('klien_hp');
            $table->text('deskripsi_keluhan')->nullable()->after('bidang_hukum');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['bidang_hukum', 'deskripsi_keluhan']);
        });

        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn(['bidang_hukum', 'deskripsi_keluhan']);
        });
    }
};
