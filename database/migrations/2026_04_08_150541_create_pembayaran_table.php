<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_pesanan');
            $table->string('midtrans_order_id')->unique(); // order_id yang dikirim ke Midtrans
            $table->string('payment_type')->nullable();    // va, qris, dll
            $table->string('status')->default('pending');  // pending, settlement, dll
            $table->integer('jumlah_bayar');
            $table->timestamps();

            $table->foreign('id_pesanan')
                  ->references('id_pesanan')->on('pesanan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};