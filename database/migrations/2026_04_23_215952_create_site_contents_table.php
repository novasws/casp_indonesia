<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();       // e.g. 'stats', 'layanan', 'cara_kerja'
            $table->string('label');                 // Human-readable label
            $table->string('group')->default('general'); // Group for organizing in admin
            $table->longText('value');               // JSON or text content
            $table->string('type')->default('text'); // text, json, textarea
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contents');
    }
};
