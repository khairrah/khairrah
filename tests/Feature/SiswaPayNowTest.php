<?php

use App\Models\User;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\DendaPayment;

it('siswa can click Bayar Sekarang to create denda payments and set status to menunggu_verifikasi', function () {
    $tool = Tool::create([
        'kode_alat' => 'ALAT-010',
        'nama_alat' => 'Alat Untuk Bayar',
        'merk' => 'MerekX',
        'lokasi' => 'Lab',
        'kondisi' => 'baik',
        'stok' => 3,
    ]);

    $siswa = User::factory()->create(['role' => 'siswa']);

    $loan = Loan::create([
        'user_id' => $siswa->id,
        'tool_id' => $tool->id,
        'jumlah' => 1,
        'tanggal_pinjam' => now()->subDays(7),
        'tanggal_kembali_target' => now()->subDays(2),
        'tanggal_kembali' => now(),
        'status' => 'returned',
        'denda' => 30000,
        'denda_status' => 'menunggu_pembayaran',
    ]);

    $this->actingAs($siswa)
        ->post(route('siswa.denda-payments.pay-now'))
        ->assertRedirect()
        ->assertSessionHas('success');

    $loan->refresh();
    expect($loan->denda_status)->toBe('menunggu_verifikasi');

    $payment = DendaPayment::where('loan_id', $loan->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe('menunggu_verifikasi');
    expect((float) $payment->jumlah_bayar)->toBe(30000.0);
});
