@extends('layouts.siswa')

@section('content')
<div class="p-6" style="background-color: #FFF7E6;">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📚 Riwayat Peminjaman</h1>
            <p class="text-gray-600">Lihat semua peminjaman Anda yang sudah dikembalikan</p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Peminjaman</p>
                <p class="text-3xl font-bold text-blue-600">{{ $historyLoans->total() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Tanpa Denda</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $historyLoans->where('denda', 0)->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Dengan Denda</p>
                <p class="text-3xl font-bold text-red-600">
                    {{ $historyLoans->where('denda', '>', 0)->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Denda Bayar</p>
                <p class="text-3xl font-bold text-orange-600">
                    Rp {{ number_format($totalDendaBayar, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($historyLoans->count() > 0)
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Target Kembali</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Status Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Denda</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($historyLoans as $loan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $loan->tool->nama_alat ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->jumlah }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->tanggal_kembali_target ? \Carbon\Carbon::parse($loan->tanggal_kembali_target)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($loan->status_alat === 'baik')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✓ Baik</span>
                                    @elseif ($loan->status_alat === 'rusak')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">⚠️ Rusak</span>
                                    @elseif ($loan->status_alat === 'hilang')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">✗ Hilang</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    @if ($loan->denda > 0)
                                        <span class="text-red-600">Rp {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                        <br>
                                        <small class="text-gray-600">
                                            @if ($loan->denda_status === 'lunas')
                                                ✓ <span class="text-green-600">Lunas</span>
                                            @else
                                                ⏳ <span class="text-orange-600">{{ ucfirst(str_replace('_', ' ', $loan->denda_status)) }}</span>
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-green-600">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($loan->denda > 0 && $loan->denda_status !== 'lunas')
                                        <a href="{{ route('siswa.denda-payments.index') }}" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                            💳 Bayar
                                        </a>
                                    @else
                                        <span class="px-3 py-2 bg-gray-300 text-gray-600 text-xs font-semibold rounded-lg">
                                            ✓ Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p>Belum ada riwayat peminjaman</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($historyLoans->hasPages())
            <div class="mt-8">
                {{ $historyLoans->links() }}
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('siswa.loans.index') }}" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg transition">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection
