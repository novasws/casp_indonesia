<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('gelar', 60)->nullable();
            $table->string('spesialisasi', 80);
            $table->unsignedTinyInteger('pengalaman_tahun')->default(1);
            $table->string('inisial', 4);
            $table->string('warna_avatar', 30)->default('blue'); // blue | green | orange | purple | red
            $table->enum('status', ['online', 'sibuk', 'offline'])->default('offline');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultans');
    }
};