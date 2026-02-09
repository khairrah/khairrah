<?php

namespace App\Helpers;

class DendaHelper
{
    // Konstanta denda BARU
    const DENDA_KETERLAMBATAN_PER_HARI = 5000;  // Berubah dari 10000 ke 5000

    /**
     * Hitung denda berdasarkan status alat dan keterlambatan
     * 
     * @param string $status_alat - 'baik', 'rusak', 'hilang'
     * @param decimal|null $harga_barang - Harga barang (untuk rusak/hilang)
     * @param string|null $tanggal_kembali_target - Tanggal batas pengembalian
     * @param string|null $tanggal_kembali_actual - Tanggal pengembalian sebenarnya (sekarang)
     * @return array ['total' => denda, 'breakdown' => penjelasan, 'keterangan' => full text]
     */
    public static function hitungDenda(
        $status_alat,
        $harga_barang = 0,
        $tanggal_kembali_target = null,
        $tanggal_kembali_actual = null
    ) {
        $total_denda = 0;
        $breakdown = [];

        // Denda berdasarkan status alat
        if ($status_alat == 'rusak') {
            // Denda rusak: 50% × harga barang
            $denda_rusak = (int) ($harga_barang * 0.5);
            $total_denda += $denda_rusak;
            $breakdown[] = "Alat rusak (50% × Rp " . number_format($harga_barang, 0, ',', '.') . "): Rp " . number_format($denda_rusak, 0, ',', '.');

        } elseif ($status_alat == 'hilang') {
            // Denda hilang: 100% × harga barang
            $denda_hilang = (int) $harga_barang;
            $total_denda += $denda_hilang;
            $breakdown[] = "Alat hilang (100% × Rp " . number_format($harga_barang, 0, ',', '.') . "): Rp " . number_format($denda_hilang, 0, ',', '.');

        } else {
            // Jika alat baik, hitung denda keterlambatan
            if ($tanggal_kembali_target && $tanggal_kembali_actual) {
                $target = \Carbon\Carbon::parse($tanggal_kembali_target);
                $actual = \Carbon\Carbon::parse($tanggal_kembali_actual);

                if ($actual->greaterThan($target)) {
                    $hari_terlambat = $actual->diffInDays($target);
                    $denda_terlambat = $hari_terlambat * self::DENDA_KETERLAMBATAN_PER_HARI;
                    $total_denda += $denda_terlambat;
                    $breakdown[] = "Keterlambatan {$hari_terlambat} hari @ Rp " . number_format(self::DENDA_KETERLAMBATAN_PER_HARI, 0, ',', '.') . "/hari = Rp " . number_format($denda_terlambat, 0, ',', '.');
                } else {
                    $breakdown[] = "Alat kembali tepat waktu";
                }
            } else {
                $breakdown[] = "Alat baik";
            }
        }

        return [
            'total' => $total_denda,
            'breakdown' => $breakdown,
            'keterangan' => implode(" | ", $breakdown) ?: "Tidak ada denda"
        ];
    }
}

