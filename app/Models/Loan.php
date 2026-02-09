<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_peminjam',
        'user_id',
        'tool_id',
        'jumlah',
        'jumlah_kembali_baik',
        'jumlah_kembali_rusak',
        'jumlah_kembali_hilang',
        'tanggal_pinjam',
        'tanggal_kembali_target',
        'tanggal_kembali',
        'catatan',
        'alasan_siswa',
        'status_alat',
        'harga_barang',
        'alasan_denda',
        'denda',
        'denda_status',
        'status'
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dendaPayments()
    {
        return $this->hasMany(DendaPayment::class);
    }

    // Helper methods untuk status denda
    public function hasDenda(): bool
    {
        return $this->denda > 0;
    }

    public function isDendaPaid(): bool
    {
        return $this->denda_status === 'lunas' || ($this->hasDenda() && $this->denda <= 0);
    }

    public function isDendaWaitingVerification(): bool
    {
        return $this->denda_status === 'menunggu_verifikasi';
    }

    public function isDendaWaitingPayment(): bool
    {
        return $this->denda_status === 'menunggu_pembayaran';
    }

    public function isPendingDendaPayment(): bool
    {
        return $this->hasDenda() && ($this->isDendaWaitingPayment() || $this->isDendaWaitingVerification());
    }

    public function getLatestPendingDendaPayment()
    {
        return $this->dendaPayments()
            ->where('status', 'menunggu_verifikasi')
            ->latest('created_at')
            ->first();
    }
}
