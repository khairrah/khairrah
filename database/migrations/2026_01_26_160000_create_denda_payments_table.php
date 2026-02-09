<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('denda_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->decimal('jumlah_denda', 10, 2)->comment('Jumlah denda sebelum pembayaran');
            $table->decimal('jumlah_bayar', 10, 2)->comment('Jumlah yang dibayarkan');
            $table->decimal('sisa_denda', 10, 2)->default(0)->comment('Sisa denda setelah pembayaran');
            $table->string('metode_pembayaran')->comment('Metode pembayaran (tunai, transfer, dll)')->default('tunai');
            $table->string('bukti_pembayaran')->nullable()->comment('File bukti pembayaran (foto/scan)');
            $table->string('status')->default('menunggu_verifikasi')->comment('menunggu_verifikasi, terverifikasi, ditolak');
            $table->text('catatan_petugas')->nullable();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->foreignId('petugas_verifikasi_id')->nullable()->constrained('users')->nullOnDelete()->comment('Petugas yang verifikasi pembayaran');
            $table->timestamps();
            $table->index('loan_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda_payments');
    }
};
