@extends('layouts.siswa')

@section('content')
<div class="p-6" style="background-color: #FFF7E6;">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('siswa.denda-payments.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Kembali ke Pembayaran Denda
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Detail Pembayaran Denda</h1>
        </div>

        <!-- Status Alert -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-lg text-green-800">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-6 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg text-yellow-800">
                ⚠️ {{ session('warning') }}
            </div>
        @endif

        <!-- Status Badge -->
        <div class="mb-6 flex gap-2 flex-wrap">
            @if ($dendaPayment->status === 'menunggu_verifikasi')
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">⏳ Menunggu Verifikasi Petugas</span>
            @elseif ($dendaPayment->status === 'terverifikasi')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">✓ Pembayaran Terverifikasi</span>
            @else
                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold">✗ Pembayaran Ditolak</span>
            @endif
            
            <!-- Status Denda Loan -->
            @if ($dendaPayment->loan->denda_status === 'lunas')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">💰 Denda Lunas</span>
            @elseif ($dendaPayment->loan->denda_status === 'menunggu_verifikasi')
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">⏳ Denda Menunggu Verifikasi</span>
            @elseif ($dendaPayment->loan->denda_status === 'menunggu_pembayaran')
                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold">💸 Denda Menunggu Pembayaran</span>
            @endif
        </div>

        <!-- Info Pembayaran -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pembayaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="text-gray-600 text-sm">Alat yang Dipinjam</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $dendaPayment->loan->tool->nama }}</p>
                </div>
                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="text-gray-600 text-sm">Tanggal Pembayaran</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $dendaPayment->tanggal_pembayaran?->format('d M Y H:i') ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-red-600 pl-4">
                    <p class="text-gray-600 text-sm">Jumlah Denda Awal</p>
                    <p class="text-lg font-semibold text-red-600">Rp {{ number_format($dendaPayment->jumlah_denda, 0, ',', '.') }}</p>
                </div>
                <div class="border-l-4 border-green-600 pl-4">
                    <p class="text-gray-600 text-sm">Jumlah Bayar</p>
                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($dendaPayment->jumlah_bayar, 0, ',', '.') }}</p>
                </div>
                <div class="border-l-4 border-orange-600 pl-4">
                    <p class="text-gray-600 text-sm">Sisa Denda</p>
                    <p class="text-lg font-semibold" :class="{ 'text-red-600': {{ $dendaPayment->sisa_denda > 0 ? 'true' : 'false' }}, 'text-green-600': {{ $dendaPayment->sisa_denda == 0 ? 'true' : 'false' }} }">
                        Rp {{ number_format($dendaPayment->sisa_denda, 0, ',', '.') }}
                    </p>
                </div>
                <div class="border-l-4 border-purple-600 pl-4">
                    <p class="text-gray-600 text-sm">Metode Pembayaran</p>
                    <p class="text-lg font-semibold text-gray-900 capitalize">{{ $dendaPayment->metode_pembayaran }}</p>
                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        @if ($dendaPayment->bukti_pembayaran)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Bukti Pembayaran</h2>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <img src="{{ asset('storage/' . $dendaPayment->bukti_pembayaran) }}" 
                         alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg">
                </div>
            </div>
        @endif

        <!-- Catatan Petugas (Jika Ditolak) -->
        @if ($dendaPayment->status === 'ditolak' && $dendaPayment->catatan_petugas)
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-bold text-red-900 mb-2">Catatan Petugas</h2>
                <p class="text-red-800">{{ $dendaPayment->catatan_petugas }}</p>
            </div>
        @endif

        <!-- Verifikasi Info (Jika Terverifikasi) -->
        @if ($dendaPayment->status === 'terverifikasi')
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-bold text-green-900 mb-3">✓ Pembayaran Terverifikasi</h2>
                <div class="space-y-2 text-green-800">
                    <p><strong>Diverifikasi oleh:</strong> {{ $dendaPayment->petugasVerifikasi->name ?? 'Petugas' }}</p>
                    <p><strong>Tanggal Verifikasi:</strong> {{ $dendaPayment->tanggal_verifikasi?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>
        @endif

        <!-- Tombol Kembali -->
        <div class="flex gap-3">
            <a href="{{ route('siswa.denda-payments.index') }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition text-center">
                Kembali ke Daftar Pembayaran
            </a>
            @if ($dendaPayment->status === 'terverifikasi')
                <a href="{{ route('siswa.denda-payments.cetak', $dendaPayment->id) }}" 
                   target="_blank"
                   class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-center">
                    🖨️ Cetak Bukti Pembayaran
                </a>
            @endif
            @if ($dendaPayment->status === 'ditolak')
                <a href="{{ route('siswa.denda-payments.create', $dendaPayment->loan_id) }}" 
                   class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-lg transition text-center">
                    Ajukan Ulang Pembayaran
                </a>
            @endif
        </div>

        <!-- Catatan Umum -->
        <div class="mt-6 bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <strong>📝 Catatan:</strong> 
                @if ($dendaPayment->status === 'menunggu_verifikasi')
                    Pembayaran Anda sedang diproses oleh petugas. Tunggu sampai petugas memverifikasi bukti pembayaran Anda.
                @elseif ($dendaPayment->status === 'terverifikasi')
                    Pembayaran Anda telah terverifikasi. Denda telah berkurang sesuai dengan jumlah yang Anda bayarkan.
                @else
                    Pembayaran Anda ditolak. Silakan ajukan ulang pembayaran dengan bukti yang benar.
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
