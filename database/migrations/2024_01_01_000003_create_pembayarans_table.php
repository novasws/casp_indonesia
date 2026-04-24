<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 40)->unique();
            $table->string('nama_klien', 100);
            $table->string('email_klien', 150);
            $table->string('hp_klien', 20);
            $table->foreignId('konsultan_id')->constrained('konsultans')->restrictOnDelete();
            $table->unsignedTinyInteger('paket'); // 1 | 2 | 3
            $table->enum('metode', ['qris', 'bca', 'gopay', 'ovo']);
            $table->unsignedInteger('harga');
            $table->unsignedInteger('biaya_layanan')->default(5000);
            $table->unsignedInteger('total');
            $table->enum('status', ['menunggu', 'lunas', 'gagal', 'refund'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};