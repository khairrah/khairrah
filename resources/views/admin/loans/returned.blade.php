@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="bg-gradient-to-r from-emerald-100 to-teal-100 p-6 rounded-xl shadow mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-700 flex items-center gap-2">
            ✅ Data Pengembalian
        </h1>
        <p class="text-sm text-gray-500">
            Daftar buku yang telah dikembalikan oleh peminjam
        </p>
    </div>
</div>

<!-- STATS BRIEF -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border shadow-sm flex items-center gap-4">
        <div class="p-3 bg-emerald-100 rounded-lg text-emerald-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase font-bold">Total Kembali</p>
            <p class="text-xl font-bold">{{ $loans->count() }} Buku</p>
        </div>
    </div>
</div>

<!-- CARD TABLE -->
<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    
    <div class="p-4 border-b bg-gray-50">
        <h2 class="font-semibold text-gray-600 text-sm">History Pengembalian</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-emerald-50 text-emerald-800 text-xs uppercase font-bold">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Peminjam</th>
                    <th class="p-4">Buku</th>
                    <th class="p-4 text-center">Jumlah</th>
                    <th class="p-4">Tgl Pinjam</th>
                    <th class="p-4">Tgl Kembali</th>
                    <th class="p-4">Denda</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="p-4">
                        <div class="font-semibold text-gray-700">{{ $loan->user->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-tighter">{{ $loan->user->role ?? 'siswa' }}</div>
                    </td>
                    <td class="p-4 font-medium text-blue-600">
                        {{ $loan->book->judul ?? '-' }}
                    </td>
                    <td class="p-4 text-center font-bold text-gray-700">
                        {{ $loan->jumlah }}
                    </td>
                    <td class="p-4 text-gray-600">
                        {{ date('d M Y', strtotime($loan->tanggal_pinjam)) }}
                    </td>
                    <td class="p-4 text-emerald-600 font-medium">
                        {{ date('d M Y', strtotime($loan->tanggal_kembali)) }}
                    </td>
                    <td class="p-4 font-bold">
                        @if($loan->denda > 0)
                            <span class="text-red-500">
                                Rp {{ number_format($loan->denda, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-gray-300 font-normal">Nihil</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200">
                            Sudah Kembali
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-10 text-center text-gray-400 italic font-medium">
                        🍃 Belum ada data pengembalian buku yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection