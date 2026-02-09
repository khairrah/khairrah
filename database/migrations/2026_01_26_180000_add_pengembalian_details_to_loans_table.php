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
        Schema::table('loans', function (Blueprint $table) {
            // Tambahkan kolom untuk tracking jumlah pengembalian per status
            if (!Schema::hasColumn('loans', 'jumlah_kembali_baik')) {
                $table->integer('jumlah_kembali_baik')->default(0)->comment('Jumlah barang yang dikembalikan dalam kondisi baik')->after('jumlah');
            }
            if (!Schema::hasColumn('loans', 'jumlah_kembali_rusak')) {
                $table->integer('jumlah_kembali_rusak')->default(0)->comment('Jumlah barang yang dikembalikan dalam kondisi rusak')->after('jumlah_kembali_baik');
            }
            if (!Schema::hasColumn('loans', 'jumlah_kembali_hilang')) {
                $table->integer('jumlah_kembali_hilang')->default(0)->comment('Jumlah barang yang hilang')->after('jumlah_kembali_rusak');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'jumlah_kembali_baik')) {
                $table->dropColumn('jumlah_kembali_baik');
            }
            if (Schema::hasColumn('loans', 'jumlah_kembali_rusak')) {
                $table->dropColumn('jumlah_kembali_rusak');
            }
            if (Schema::hasColumn('loans', 'jumlah_kembali_hilang')) {
                $table->dropColumn('jumlah_kembali_hilang');
            }
        });
    }
};
