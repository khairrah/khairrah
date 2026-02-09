<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'tanggal_kembali_target')) {
                $table->date('tanggal_kembali_target')->nullable()->after('tanggal_pinjam');
            }
            if (!Schema::hasColumn('loans', 'status_alat')) {
                $table->enum('status_alat', ['baik', 'rusak', 'hilang'])->nullable()->after('tanggal_kembali');
            }
            if (!Schema::hasColumn('loans', 'alasan_denda')) {
                $table->text('alasan_denda')->nullable()->after('status_alat');
            }
            if (!Schema::hasColumn('loans', 'denda')) {
                $table->decimal('denda', 10, 2)->default(0)->after('alasan_denda');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'tanggal_kembali_target')) {
                $table->dropColumn('tanggal_kembali_target');
            }
            if (Schema::hasColumn('loans', 'status_alat')) {
                $table->dropColumn('status_alat');
            }
            if (Schema::hasColumn('loans', 'alasan_denda')) {
                $table->dropColumn('alasan_denda');
            }
            if (Schema::hasColumn('loans', 'denda')) {
                $table->dropColumn('denda');
            }
        });
    }
};
