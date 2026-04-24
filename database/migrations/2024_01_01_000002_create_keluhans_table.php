<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('hp', 20);
            $table->string('email', 150);
            $table->string('kategori', 60);
            $table->string('urgensi', 40)->default('Normal (1-3 hari)');
            $table->text('isi');
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluhans');
    }
};