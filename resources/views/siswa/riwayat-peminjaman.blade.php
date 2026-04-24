@extends('layouts.siswa')

@section('content')

<div class="p-2">
    <!-- Header -->
    <div class="bg-[#fef9e7] p-5 rounded-2xl border border-[#f5efd7] shadow-sm mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 text-xl">
                📜
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Riwayat Peminjaman</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Lihat semua peminjaman Anda yang sudah dikembalikan</p>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-50 shadow-sm p-4">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Total Pinjam</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $historyLoans->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-50 shadow-sm p-4">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Tanpa Denda</p>
            <p class="text-2xl font-bold text-green-600 mt-1">
                {{ $historyLoans->where('denda', 0)->count() }}
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-50 shadow-sm p-4">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Dengan Denda</p>
            <p class="text-2xl font-bold text-red-600 mt-1">
                {{ $historyLoans->where('denda', '>', 0)->count() }}
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-50 shadow-sm p-4">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Total Denda</p>
            <p class="text-2xl font-bold text-orange-600 mt-1">
                Rp{{ number_format($totalDendaBayar, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-3 border-b bg-gray-50/50">
            <h2 class="font-bold text-gray-500 text-[12px] tracking-wide">Data Pengembalian</h2>
        </div>

        <div class="overflow-x-auto">
            @if ($historyLoans->count() > 0)
                <table class="w-full text-[12px]">
                    <thead class="bg-white text-gray-400 text-[10px] uppercase tracking-widest border-b text-left">
                        <tr>
                            <th class="p-3 font-bold">No</th>
                            <th class="p-3 font-bold">Buku / Alat</th>
                            <th class="p-3 font-bold text-center">Jumlah</th>
                            <th class="p-3 font-bold">Tanggal Pinjam</th>
                            <th class="p-3 font-bold">Kembali</th>
                            <th class="p-3 font-bold text-center">Status</th>
                            <th class="p-3 font-bold">Denda</th>
                            <th class="p-3 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($historyLoans as $loan)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="p-3 font-bold text-gray-700">
                                    {{ $loan->book->judul ?? ($loan->tool->nama_alat ?? '-') }}
                                </td>
                                <td class="p-3 text-center">{{ $loan->jumlah }}</td>
                                <td class="p-3 text-gray-500">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/y') }}</td>
                                <td class="p-3 text-gray-500">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/y') }}</td>
                                <td class="p-3 text-center">
                                    @if ($loan->status_alat === 'baik')
                                        <span class="px-2 py-0.5 bg-green-50 text-green-600 rounded-lg text-[9px] font-bold">BAIK</span>
                                    @elseif ($loan->status_alat === 'rusak')
                                        <span class="px-2 py-0.5 bg-yellow-50 text-yellow-600 rounded-lg text-[9px] font-bold">RUSAK</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-red-50 text-red-600 rounded-lg text-[9px] font-bold">{{ strtoupper($loan->status_alat) }}</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if ($loan->denda > 0)
                                        <span class="text-red-600 font-bold">Rp{{ number_format($loan->denda, 0, ',', '.') }}</span>
                                        <br>
                                        <span class="text-[9px] {{ $loan->denda_status === 'lunas' ? 'text-green-500' : 'text-orange-500' }} font-bold uppercase">
                                            {{ $loan->denda_status }}
                                        </span>
                                    @else
                                        <span class="text-green-600 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    @if ($loan->denda > 0 && $loan->denda_status !== 'lunas')
                                        <a href="{{ route('siswa.denda-payments.index') }}" class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg border border-red-100">
                                            Bayar
                                        </a>
                                    @else
                                        <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-bold rounded-lg border border-gray-100">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-10 text-center text-gray-400">
                    <p class="text-sm">Belum ada riwayat peminjaman</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
