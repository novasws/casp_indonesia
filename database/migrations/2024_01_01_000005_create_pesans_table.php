<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsultasi_id')->constrained('konsultasis')->cascadeOnDelete();
            $table->enum('pengirim', ['klien', 'konsultan']);
            $table->text('isi');
            $table->timestamps();

            $table->index(['konsultasi_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};