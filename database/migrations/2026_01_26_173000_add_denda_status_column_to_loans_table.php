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
        // Jika kolom sudah ada (mis. dari migrasi sebelumnya), skip untuk menghindari SQL duplicate
        if (!Schema::hasColumn('loans', 'denda_status')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->string('denda_status')->default('tidak_ada_denda')->comment('tidak_ada_denda, menunggu_pembayaran, menunggu_verifikasi, lunas, ditolak')->after('denda');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('loans', 'denda_status')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->dropColumn('denda_status');
            });
        }
    }
};
