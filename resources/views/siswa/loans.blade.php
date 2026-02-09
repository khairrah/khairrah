@extends('layouts.siswa')

@section('content')

<!-- Header -->
<div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
    <h1 class="text-4xl font-bold drop-shadow-md" style="color: #374151;">
        📋 Peminjaman Saya
    </h1>
    <p class="mt-2 drop-shadow-sm" style="color: #374151;">
        Kelola peminjaman dan pengembalian alat Anda
    </p>
</div>

<!-- Tombol Ajukan Pinjaman -->
<div class="mb-6">
    <a href="{{ route('siswa.loans.create') }}" 
       class="inline-block px-6 py-2 rounded font-semibold transition"
       style="background-color: #CDEDEA; color: #374151;">
        ➕ Ajukan Pinjaman Baru
    </a>
</div>

<!-- Peminjaman Aktif -->
<div class="rounded-lg shadow-lg p-6 mb-8" style="background-color: #DCEBFA;">
    <h2 class="text-2xl font-bold mb-4" style="color: #374151;">📦 Peminjaman Aktif</h2>

    @php
        $myLoans = \App\Models\Loan::where('user_id', auth()->id())
            ->whereNull('tanggal_kembali')
            ->with('tool')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
    @endphp

    @if($myLoans->count())
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead style="background-color: #CDEDEA;">
                    <tr>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Alat</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Jumlah</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal Pinjam</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Batas Kembali</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Alasan (jika rusak/hilang)</th>
                        <th class="px-4 py-2 text-right" style="color: #374151;">💰 Denda</th>
                        <th class="px-4 py-2 text-center" style="color: #374151;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myLoans as $loan)
                    <tr class="border-b" style="background-color: #FFF7E6;">
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tool->nama_alat ?? '-' }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->jumlah }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tanggal_pinjam }}</td>
                        <td class="px-4 py-2" style="color: #374151;">
                            @if($loan->tanggal_kembali_target)
                                <strong style="color: #0891b2;">{{ $loan->tanggal_kembali_target }}</strong>
                            @else
                                <span style="color: #9CA3AF; font-style: italic;">Belum disetujui</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-left" style="font-size: 0.9rem;">
                            @if($loan->status === 'approved')
                                <button onclick="openAlasanModal({{ $loan->id }}, '{{ $loan->tool->nama_alat }}')" class="px-3 py-1 rounded text-sm font-semibold" style="background-color: #DBEAFE; color: #1e40af; text-decoration: none; cursor: pointer;">
                                    {{ $loan->alasan_siswa ? '✏️ Lihat/Edit' : '➕ Tambah Laporan' }}
                                </button>
                                @if($loan->alasan_siswa)
                                    <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #FEF3C7; border-left: 3px solid #F59E0B; font-size: 0.85rem;">
                                        {{ $loan->alasan_siswa }}
                                    </div>
                                @endif
                            @else
                                <span style="color: #9CA3AF; font-style: italic; font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            @if($loan->denda && $loan->denda > 0)
                                <div style="color: #991B1B; font-weight: bold;">
                                    Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                </div>
                                <!-- Status Denda -->
                                <div style="margin-top: 0.3rem;">
                                    @if($loan->denda_status === 'menunggu_pembayaran')
                                        <div style="font-size: 0.75rem; color: #991B1B; font-weight: bold;">
                                            💸 Menunggu Pembayaran
                                        </div>
                                    @elseif($loan->denda_status === 'menunggu_verifikasi')
                                        <div style="font-size: 0.75rem; color: #92400E; font-weight: bold;">
                                            ⏳ Menunggu Verifikasi
                                        </div>
                                    @endif
                                </div>
                                @if($loan->status === 'approved' && $loan->denda_status === 'menunggu_pembayaran')
                                    <a href="{{ route('siswa.denda-payments.create', $loan->id) }}" 
                                       class="text-xs mt-1 inline-block px-2 py-1 rounded font-semibold" 
                                       style="background-color: #DC2626; color: white; text-decoration: none;">
                                        Bayar Sekarang
                                    </a>
                                @endif
                            @else
                                <span style="color: #059669; font-weight: bold;">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if($loan->status === 'pending')
                                <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #FEF3C7; color: #92400E;">
                                    ⏳ Menunggu Approval
                                </span>
                            @elseif($loan->status === 'approved')
                                <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #CDEDEA; color: #374151;">
                                    ✓ Disetujui
                                </span>
                            @elseif($loan->status === 'rejected')
                                <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #FCA5A5; color: #7F1D1D;">
                                    ✗ Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #CDEDEA; color: #374151;">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8" style="color: #374151;">
            <p class="text-lg">Tidak ada peminjaman aktif</p>
        </div>
    @endif
</div>

<!-- Riwayat Peminjaman -->
<div class="rounded-lg shadow-lg p-6" style="background-color: #DCEBFA;">
    <h2 class="text-2xl font-bold mb-4" style="color: #374151;">📚 Riwayat Peminjaman</h2>

    @php
        $historyLoans = \App\Models\Loan::where('user_id', auth()->id())
            ->whereNotNull('tanggal_kembali')
            ->with('tool')
            ->orderBy('tanggal_kembali', 'desc')
            ->get();
    @endphp

    @if($historyLoans->count())
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead style="background-color: #CDEDEA;">
                    <tr>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Alat</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Jumlah</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal Pinjam</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyLoans as $loan)
                    <tr class="border-b" style="background-color: #FFF7E6;">
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tool->nama_alat ?? '-' }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->jumlah }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tanggal_pinjam }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tanggal_kembali }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8" style="color: #374151;">
            <p class="text-lg">Belum ada riwayat peminjaman</p>
        </div>
    @endif
</div>

<!-- Modal Alasan Rusak/Hilang -->
<div id="alasanModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 50;">
    <div style="border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 32rem; margin-left: 1rem; margin-right: 1rem; background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151;">Laporan Kerusakan/Hilang</h2>
            <button onclick="closeAlasanModal()" style="color: #9CA3AF; cursor: pointer; font-size: 1.5rem;">×</button>
        </div>
        <p style="margin-bottom: 1rem; color: #374151;">Alat: <strong id="alasanToolName"></strong></p>
        <form id="alasanForm" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
            @csrf
            @method('PATCH')
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Jelaskan kondisi alat (rusak/hilang):</label>
                <textarea name="alasan_siswa" required style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: #374151; min-height: 100px;" placeholder="Contoh: Layar rusak karena jatuh, atau: Alat hilang di ruang praktik"></textarea>
                <small style="color: #6B7280; display: block; margin-top: 0.25rem;">Laporan ini akan dilihat oleh petugas untuk menentukan harga denda</small>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" style="flex: 1; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #3B82F6; color: white; cursor: pointer;">
                    Simpan Laporan
                </button>
                <button type="button" onclick="closeAlasanModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAlasanModal(id, toolName) {
        document.getElementById('alasanToolName').textContent = toolName;
        document.getElementById('alasanForm').action = `/siswa/loans/${id}/alasan`;
        document.getElementById('alasanModal').style.display = 'flex';
    }

    function closeAlasanModal() {
        document.getElementById('alasanModal').style.display = 'none';
    }

    document.getElementById('alasanModal').addEventListener('click', function(e) {
        if (e.target === this) closeAlasanModal();
    });
</script>

@endsection
