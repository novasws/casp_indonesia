<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsultans', function (Blueprint $table) {
            $table->string('jadwal_shift')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('konsultans', function (Blueprint $table) {
            $table->dropColumn('jadwal_shift');
        });
    }
};
