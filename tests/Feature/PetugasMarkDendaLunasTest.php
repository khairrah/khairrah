<?php

use App\Models\User;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\DendaPayment;

it('petugas can mark loan denda as lunas and creates or verifies a denda payment', function () {
    // Seed necessary models
    $tool = Tool::create([
        'kode_alat' => 'ALAT-001',
        'nama_alat' => 'Test Alat',
        'merk' => 'Merek',
        'lokasi' => 'Lab',
        'kondisi' => 'baik',
        'stok' => 10,
    ]);

    $siswa = User::factory()->create(['role' => 'siswa']);
    $petugas = User::factory()->create(['role' => 'petugas']);

    // Create a loan with denda
    $loan = Loan::create([
        'user_id' => $siswa->id,
        'tool_id' => $tool->id,
        'jumlah' => 1,
        'tanggal_pinjam' => now()->subDays(10),
        'tanggal_kembali_target' => now()->subDays(3),
        'tanggal_kembali' => now(),
        'status' => 'returned',
        'denda' => 50000,
        'denda_status' => 'menunggu_pembayaran',
    ]);

    // Act as petugas and hit the route
    $this->actingAs($petugas)
        ->post(route('petugas.mark-denda-lunas', $loan->id))
        ->assertStatus(302)
        ->assertSessionHas('success');

    // loan updated
    $loan->refresh();
    expect($loan->denda)->toBe(0);
    expect($loan->denda_status)->toBe('lunas');

    // a DendaPayment exists and is terverifikasi
    $payment = DendaPayment::where('loan_id', $loan->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe('terverifikasi');
    expect((float) $payment->jumlah_bayar)->toBe((float) 50000);
});
