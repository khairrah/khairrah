@extends('layouts.petugas')

@section('content')

<!-- Header Section -->
<div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
    <h1 class="text-4xl font-bold drop-shadow-md" style="color: #374151;">Halo, {{ auth()->user()->name }}! 👋</h1>
    <p class="mt-2 drop-shadow-sm" style="color: #374151;">Selamat datang di dashboard petugas</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Peminjaman Pending -->
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FFF1E6;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold" style="color: #374151;">⏳ Peminjaman Menunggu</p>
                <p class="text-3xl font-bold mt-2" style="color: #374151;">
                    {{ \App\Models\Loan::where('status', 'pending')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Total Peminjaman Disetujui -->
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FFF1E6;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold" style="color: #374151;">✅ Peminjaman Disetujui</p>
                <p class="text-3xl font-bold mt-2" style="color: #374151;">
                    {{ \App\Models\Loan::where('status', 'approved')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Total Pengembalian Pending -->
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FFF1E6;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold" style="color: #374151;">⏳ Pengembalian Menunggu</p>
                <p class="text-3xl font-bold mt-2" style="color: #374151;">
                    {{ \App\Models\Loan::where('status', 'approved')->whereNull('tanggal_kembali')->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Peminjaman Menunggu Persetujuan -->
<div class="rounded-lg shadow-lg p-6" style="background-color: #DCEBFA;">
    <h2 class="text-2xl font-bold mb-4" style="color: #374151;">📋 Peminjaman Menunggu Persetujuan</h2>

    @php
        $pendingLoans = \App\Models\Loan::where('status', 'pending')
            ->with('user', 'tool')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    @endphp

    @if($pendingLoans->count())
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead style="background-color: #CDEDEA;">
                    <tr>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Nama Peminjam</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Alat</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Jumlah</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingLoans as $loan)
                    <tr class="border-b" style="background-color: #FFF7E6;">
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->nama_peminjam }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tool->nama_alat ?? '-' }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->jumlah }}</td>
                        <td class="px-4 py-2 text-sm" style="color: #6B7280;">{{ $loan->tanggal_pinjam }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('petugas.approve-loans') }}" class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #CDEDEA; color: #374151; text-decoration: none;">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8" style="color: #374151;">
            <p class="text-lg">Tidak ada peminjaman menunggu</p>
        </div>
    @endif
</div>

@endsection
