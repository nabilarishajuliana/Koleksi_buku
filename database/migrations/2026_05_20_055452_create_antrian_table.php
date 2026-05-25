<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_antrian');
            $table->string('nama');
            // Status: menunggu, dipanggil, terlambat
            $table->enum('status', ['menunggu', 'dipanggil', 'terlambat'])
                  ->default('menunggu');
            $table->timestamp('waktu_daftar')->useCurrent();
            $table->timestamp('waktu_panggil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};