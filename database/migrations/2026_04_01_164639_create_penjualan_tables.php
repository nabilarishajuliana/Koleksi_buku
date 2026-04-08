<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel header transaksi
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
        });

        // Tabel detail isi keranjang per transaksi
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->increments('idpenjualan_detail');
            $table->unsignedInteger('id_penjualan');
            $table->unsignedBigInteger('id_barang_fk'); // merujuk ke kolom id (bigint) bukan id_barang
            $table->smallInteger('jumlah');
            $table->integer('subtotal');

            $table->foreign('id_penjualan')
                ->references('id_penjualan')->on('penjualan')
                ->onDelete('cascade');
            $table->foreign('id_barang_fk')
                ->references('id')->on('barang')  // ← rujuk ke kolom id, bukan id_barang
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
    }
};
