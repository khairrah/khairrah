@extends('layouts.siswa')

@section('content')

<!-- Header -->
<div class="mb-8 rounded-xl p-8 shadow-sm border border-blue-100 bg-gradient-to-r from-blue-50 to-indigo-50">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-white rounded-lg shadow-sm">
            <span class="text-3xl">↩️</span>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Kembalikan Buku
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Daftar buku yang sedang Anda pinjam. Silahkan bawa buku ke petugas untuk proses pengembalian.
            </p>
        </div>
    </div>
</div>

@php
$myLoans = \App\Models\Loan::with('book')
    ->where('user_id', auth()->id())
    ->where('status', 'approved')
    ->whereNull('tanggal_kembali')
    ->latest()
    ->get();
@endphp

<!-- Daftar Buku yang Harus Dikembalikan -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
        <h2 class="font-bold text-gray-700 flex items-center gap-2">
            📦 Buku di Tangan Anda
        </h2>
        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
            {{ $myLoans->count() }} Buku
        </span>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($myLoans as $loan)
                <div class="relative group p-5 rounded-xl border border-gray-100 bg-gray-50 hover:border-blue-200 hover:bg-blue-50 transition-all duration-300 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-2xl shadow-sm">
                                📖
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $loan->book->judul ?? '-' }}</h3>
                                <p class="text-xs text-gray-400">ID Pinjam: #{{ $loan->id }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Jumlah Pinjam:</span>
                            <span class="font-semibold text-gray-800">{{ $loan->jumlah }} Ekspl</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tanggal Pinjam:</span>
                            <span class="font-semibold text-gray-800">{{ date('d M Y', strtotime($loan->tanggal_pinjam)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Batas Kembali:</span>
                            <span class="font-semibold text-orange-600">{{ $loan->tanggal_kembali_target ? date('d M Y', strtotime($loan->tanggal_kembali_target)) : '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-center">
                         <div class="flex items-center gap-2 text-xs font-medium text-blue-600 italic">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Bawa buku ini ke meja petugas
                         </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-4xl mb-4 grayscale opacity-50">
                        📭
                    </div>
                    <h3 class="font-bold text-gray-400 text-lg">Tidak ada buku yang sedang dipinjam</h3>
                    <p class="text-sm text-gray-400 max-w-xs mx-auto mt-1">
                        Anda tidak memiliki pinjaman aktif yang perlu dikembalikan saat ini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Info Penting -->
<div class="mt-8 p-5 rounded-xl bg-orange-50 border border-orange-100 flex gap-4 items-start">
    <div class="p-2 bg-white rounded-lg shadow-sm text-orange-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    </div>
    <div>
        <h4 class="font-bold text-orange-800">Penting:</h4>
        <p class="text-sm text-orange-700 leading-relaxed">
            Keterlambatan pengembalian buku dapat dikenakan denda sesuai dengan peraturan perpustakaan. Pastikan buku dalam keadaan baik saat dikembalikan.
        </p>
    </div>
</div>

@endsection