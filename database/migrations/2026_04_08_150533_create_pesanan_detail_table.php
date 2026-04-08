<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->increments('id_pesanan_detail');
            $table->unsignedInteger('id_pesanan');
            $table->unsignedInteger('id_menu');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('id_pesanan')
                  ->references('id_pesanan')->on('pesanan')
                  ->onDelete('cascade');
            $table->foreign('id_menu')
                  ->references('id_menu')->on('menu')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
    }
};