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
            // Tambah kolom untuk alasan kerusakan/hilang dari siswa
            if (!Schema::hasColumn('loans', 'alasan_siswa')) {
                $table->text('alasan_siswa')->nullable()->after('catatan')->comment('Alasan kerusakan/hilang dari siswa');
            }
            
            // Tambah kolom harga barang yang ditentukan petugas
            if (!Schema::hasColumn('loans', 'harga_barang')) {
                $table->decimal('harga_barang', 10, 2)->default(0)->after('alasan_siswa')->comment('Harga barang untuk perhitungan denda (ditentukan petugas)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['alasan_siswa', 'harga_barang']);
        });
    }
};
