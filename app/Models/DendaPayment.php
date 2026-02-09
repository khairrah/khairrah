<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DendaPayment extends Model
{
    protected $fillable = [
        'loan_id',
        'jumlah_denda',
        'jumlah_bayar',
        'sisa_denda',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan_petugas',
        'tanggal_pembayaran',
        'tanggal_verifikasi',
        'petugas_verifikasi_id',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'jumlah_denda' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'sisa_denda' => 'decimal:2',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function petugasVerifikasi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_verifikasi_id');
    }

    // Status pembayaran
    public function isWaitingVerification(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    public function isVerified(): bool
    {
        return $this->status === 'terverifikasi';
    }

    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }

    public function markAsVerified($petugasId)
    {
        // Gunakan update langsung untuk menghindari kasus di mana model dianggap 'baru' oleh Eloquent saat tes
        $this->update([
            'status' => 'terverifikasi',
            'tanggal_verifikasi' => now(),
            'petugas_verifikasi_id' => $petugasId,
        ]);

        // Update loan denda status akan dilakukan di controller untuk lebih mudah di-maintain
    }

    public function markAsRejected($catatan, $petugasId)
    {
        $this->update([
            'status' => 'ditolak',
            'catatan_petugas' => $catatan,
            'tanggal_verifikasi' => now(),
            'petugas_verifikasi_id' => $petugasId,
        ]);
    }
}
