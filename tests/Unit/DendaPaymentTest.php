<?php

use App\Models\User;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\DendaPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('markAsVerified sets status, petugas_verifikasi_id and tanggal_verifikasi', function () {
    $tool = Tool::create([
        'kode_alat' => 'ALAT-002',
        'nama_alat' => 'Unit Alat',
        'merk' => 'MerekUnit',
        'lokasi' => 'Gudang',
        'kondisi' => 'baik',
        'stok' => 5,
    ]);
    $siswa = User::factory()->create(['role' => 'siswa']);
    $petugas = User::factory()->create(['role' => 'petugas']);

    $loan = Loan::create([
        'user_id' => $siswa->id,
        'tool_id' => $tool->id,
        'jumlah' => 1,
        'tanggal_pinjam' => now()->subDays(7),
        'tanggal_kembali_target' => now()->subDays(1),
        'tanggal_kembali' => now(),
        'status' => 'returned',
        'denda' => 20000,
        'denda_status' => 'menunggu_pembayaran',
    ]);

    $payment = DendaPayment::create([
        'loan_id' => $loan->id,
        'jumlah_denda' => 20000,
        'jumlah_bayar' => 20000,
        'sisa_denda' => 0,
        'metode_pembayaran' => 'tunai',
        'status' => 'menunggu_verifikasi',
        'tanggal_pembayaran' => now(),
    ]);

    $payment->markAsVerified($petugas->id);
    $payment->refresh();

    expect($payment->status)->toBe('terverifikasi');
    expect($payment->petugas_verifikasi_id)->toBe($petugas->id);
    expect($payment->tanggal_verifikasi)->not->toBeNull();
});
