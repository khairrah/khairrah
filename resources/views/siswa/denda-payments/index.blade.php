@extends('layouts.siswa')

@section('content')
<div class="p-6" style="background-color: #FFF7E6;">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Denda</h1>
            <p class="text-gray-600">Kelola pembayaran denda untuk peminjaman alat Anda</p>
        </div>

        <!-- Alert Total Denda -->
        @if ($totalDendaBelumLunas > 0)
            <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-lg flex items-center gap-4">
                <div class="text-3xl text-red-500">⚠️</div>
                <div>
                    <p class="font-semibold text-red-800">Anda memiliki denda yang belum lunas</p>
                    <p class="text-red-700 text-lg font-bold">Total: Rp {{ number_format($totalDendaBelumLunas, 0, ',', '.') }}</p>
                </div>
            </div>
        @else
            <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-lg flex items-center gap-4">
                <div class="text-3xl text-green-500">✓</div>
                <p class="font-semibold text-green-800">Semua denda Anda sudah lunas</p>
            </div>
        @endif

        <!-- Cards Status Pembayaran -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $payments->where('status', 'menunggu_verifikasi')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Terverifikasi</p>
                <p class="text-2xl font-bold text-green-600">{{ $payments->where('status', 'terverifikasi')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-gray-600 text-sm">Ditolak</p>
                <p class="text-2xl font-bold text-red-600">{{ $payments->where('status', 'ditolak')->count() }}</p>
            </div>
        </div>

        <!-- Tabel Pembayaran -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Pembayaran</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah Denda</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah Bayar</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Sisa Denda</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->tanggal_pembayaran?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($payment->jumlah_denda, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm font-semibold" :class="{ 'text-red-600': {{ $payment->sisa_denda > 0 ? 'true' : 'false' }}, 'text-green-600': {{ $payment->sisa_denda == 0 ? 'true' : 'false' }} }">
                                Rp {{ number_format($payment->sisa_denda, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($payment->status === 'menunggu_verifikasi')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Menunggu Verifikasi</span>
                                @elseif ($payment->status === 'terverifikasi')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Terverifikasi</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('siswa.denda-payments.show', $payment->id) }}" 
                                   class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <p>Belum ada riwayat pembayaran denda</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($payments->hasPages())
            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
