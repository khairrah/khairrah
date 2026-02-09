@extends('layouts.admin')

@section('content')
    <!-- Header Section with Background -->
    <div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
        <h1 class="text-4xl font-bold drop-shadow-md" style="color: #374151;">Halo, {{ auth()->user()->name }}! 👋</h1>
        <p class="mt-2 drop-shadow-sm" style="color: #374151;">Siap untuk mengelola perpustakaan hari ini?</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Alat -->
        <div class="rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition" style="background-color: #FFF1E6;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold opacity-90" style="color: #374151;">Total Alat</p>
                    <p class="text-3xl font-bold mt-2" style="color: #374151;">{{ \App\Models\Tool::count() }}</p>
                </div>
                <div class="text-5xl opacity-60">📦</div>
            </div>
        </div>

        <!-- Total Peminjaman -->
        <div class="rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition" style="background-color: #FFF1E6;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold opacity-90" style="color: #374151;">Total Peminjaman</p>
                    <p class="text-3xl font-bold mt-2" style="color: #374151;">{{ \App\Models\Loan::count() }}</p>
                </div>
                <div class="text-5xl opacity-60">📊</div>
            </div>
        </div>

        <!-- Alat Dipinjam -->
        <div class="rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition" style="background-color: #FFF1E6;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold opacity-90" style="color: #374151;">Alat Dipinjam</p>
                    <p class="text-3xl font-bold mt-2" style="color: #374151;">
                        {{ \App\Models\Loan::whereNull('tanggal_kembali')->count() }}
                    </p>
                </div>
                <div class="text-5xl opacity-60">👁️</div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition" style="background-color: #FFF1E6;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold opacity-90" style="color: #374151;">Total Pengguna</p>
                    <p class="text-3xl font-bold mt-2" style="color: #374151;">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="text-5xl opacity-60">👥</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Loans -->
        <div class="rounded-lg shadow-lg p-6" style="background-color: #DCEBFA;">
            <h2 class="text-xl font-bold mb-4" style="color: #374151;">Peminjaman Terbaru</h2>
            <div class="space-y-3">
                @forelse(\App\Models\Loan::latest()->take(3)->get() as $loan)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $loan->nama_peminjam }}</p>
                            <p class="text-sm text-gray-500">{{ $loan->tool->nama_alat ?? 'Alat' }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full 
                            {{ $loan->tanggal_kembali ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $loan->tanggal_kembali ? 'Dikembalikan' : 'Dipinjam' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada peminjaman</p>
                @endforelse
            </div>
        </div>

        <!-- Available Tools -->
        <div class="rounded-lg shadow-lg p-6" style="background-color: #DCEBFA;">
            <h2 class="text-xl font-bold mb-4" style="color: #374151;">Alat Tersedia</h2>
            <div class="space-y-3">
                @forelse(\App\Models\Tool::where('stok', '>', 0)->take(3)->get() as $tool)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $tool->nama_alat }}</p>
                            <p class="text-sm text-gray-500">Stok: {{ $tool->stok }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-800">
                            Tersedia
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada alat tersedia</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
