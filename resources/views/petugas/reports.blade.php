@extends('layouts.petugas')

@section('content')
<style>
    @media print {
        /* Hide sidebar saat print */
        .sidebar-petugas {
            display: none !important;
        }
        
        /* Full width untuk konten saat print */
        body {
            margin: 0 !important;
        }
        
        /* Hide button cetak saat print */
        button, a[onclick*="print"] {
            display: none !important;
        }
    }
</style>

<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Laporan Peminjaman</h1>

<div class="mb-4 flex gap-2">
    <a href="{{ route('petugas.reports') }}?filter=all" class="px-4 py-2 rounded font-semibold" style="background-color: {{ request('filter') === 'all' || !request('filter') ? '#CDEDEA' : '#FFF7E6' }}; color: #374151; border: 1px solid #CDEDEA; text-decoration: none;">
        Semua
    </a>
    <a href="{{ route('petugas.reports') }}?filter=pending" class="px-4 py-2 rounded font-semibold" style="background-color: {{ request('filter') === 'pending' ? '#CDEDEA' : '#FFF7E6' }}; color: #374151; border: 1px solid #CDEDEA; text-decoration: none;">
        Menunggu
    </a>
    <a href="{{ route('petugas.reports') }}?filter=approved" class="px-4 py-2 rounded font-semibold" style="background-color: {{ request('filter') === 'approved' ? '#CDEDEA' : '#FFF7E6' }}; color: #374151; border: 1px solid #CDEDEA; text-decoration: none;">
        Disetujui
    </a>
    <a href="{{ route('petugas.reports') }}?filter=rejected" class="px-4 py-2 rounded font-semibold" style="background-color: {{ request('filter') === 'rejected' ? '#CDEDEA' : '#FFF7E6' }}; color: #374151; border: 1px solid #CDEDEA; text-decoration: none;">
        Ditolak
    </a>
    <button onclick="window.print()" class="px-4 py-2 rounded font-semibold" style="background-color: #5B9FFF; color: white; border: none; cursor: pointer;">
        🖨️ Cetak
    </button>
</div>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">No</th>
            <th class="px-4 py-2" style="color: #374151;">Nama Peminjam</th>
            <th class="px-4 py-2" style="color: #374151;">Alat</th>
            <th class="px-4 py-2" style="color: #374151;">Jumlah</th>
            <th class="px-4 py-2" style="color: #374151;">Tanggal Pinjam</th>
            <th class="px-4 py-2" style="color: #374151;">Tanggal Kembali</th>
            <th class="px-4 py-2" style="color: #374151;">Status Alat</th>
            <th class="px-4 py-2" style="color: #374151;">Denda</th>
            <th class="px-4 py-2" style="color: #374151;">Alasan</th>
            <th class="px-4 py-2" style="color: #374151;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($loans as $index => $loan)
        <tr class="border-b" style="background-color: #FFF7E6;">
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $loan->nama_peminjam }}</td>
            <td class="px-4 py-2">{{ $loan->tool->nama_alat ?? '-' }}</td>
            <td class="px-4 py-2">{{ $loan->jumlah }}</td>
            <td class="px-4 py-2">{{ $loan->tanggal_pinjam }}</td>
            <td class="px-4 py-2">{{ $loan->tanggal_kembali ?? '-' }}</td>
            <td class="px-4 py-2">
                @if($loan->status_alat)
                    @if($loan->status_alat === 'baik')
                        <span style="background-color: #CDEDEA; color: #374151; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem;">✓ Baik</span>
                    @elseif($loan->status_alat === 'rusak')
                        <span style="background-color: #FCA5A5; color: #7F1D1D; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem;">⚠️ Rusak</span>
                    @elseif($loan->status_alat === 'hilang')
                        <span style="background-color: #FECACA; color: #7F1D1D; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem;">✗ Hilang</span>
                    @endif
                @else
                    <span style="color: #9CA3AF;">-</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if($loan->denda > 0)
                    <strong style="color: #DC2626;">Rp {{ number_format($loan->denda, 0, ',', '.') }}</strong>
                @else
                    <span style="color: #16A34A;">Rp 0</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if($loan->alasan_denda)
                    <span style="font-size: 0.85rem; color: #374151;">{{ Str::limit($loan->alasan_denda, 30) }}</span>
                @else
                    <span style="color: #9CA3AF; font-size: 0.85rem;">-</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if($loan->status === 'pending')
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold text-sm">Menunggu</span>
                @elseif($loan->status === 'approved')
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold text-sm">Disetujui</span>
                @elseif($loan->status === 'returned')
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded font-semibold text-sm">Dikembalikan</span>
                @else
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded font-semibold text-sm">Ditolak</span>
                @endif
            </td>
        </tr>
        @empty
        <tr class="border-b">
            <td colspan="10" class="px-4 py-2 text-center" style="color: #6B7280;">Tidak ada data peminjaman</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<style media="print">
    body {
        background-color: white;
    }
    .px-4, .py-2 {
        padding: 8px !important;
    }
    button {
        display: none;
    }
    a {
        display: none;
    }
</style>

@endsection
