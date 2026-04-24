<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id();
            $table->string('klien_nama', 100);
            $table->string('klien_email', 150);
            $table->string('klien_hp', 20);
            $table->foreignId('konsultan_id')->constrained('konsultans')->restrictOnDelete();
            $table->foreignId('pembayaran_id')->nullable()->constrained('pembayarans')->nullOnDelete();
            $table->unsignedTinyInteger('paket'); // 1 | 2 | 3
            $table->enum('status', ['aktif', 'selesai', 'menunggu', 'terjadwal'])->default('menunggu');
            $table->timestamp('mulai_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};