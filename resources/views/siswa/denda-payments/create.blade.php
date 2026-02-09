@extends('layouts.siswa')

@section('content')
<div class="p-6" style="background-color: #FFF7E6;">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('siswa.loans.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Kembali ke Daftar Peminjaman
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Pembayaran Denda</h1>
            <p class="text-gray-600 mt-2">Alat: <strong>{{ $loan->tool->nama }}</strong></p>
        </div>

        <!-- Info Denda -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Denda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Tanggal Pinjam</p>
                    <p class="text-lg font-semibold text-blue-900">{{ $loan->tanggal_pinjam->format('d M Y') }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Tanggal Kembali (Target)</p>
                    <p class="text-lg font-semibold text-blue-900">
                        {{ $loan->tanggal_kembali_target?->format('d M Y') ?? 'Belum disetujui' }}
                    </p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Status Alat</p>
                    <p class="text-lg font-semibold text-red-900 capitalize">{{ $loan->status_alat ?? 'Belum ada data' }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Total Denda</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($loan->denda, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <form action="{{ route('siswa.denda-payments.store', $loan->id) }}" method="POST" enctype="multipart/form-data" 
              class="bg-white rounded-lg shadow-lg p-6">
            @csrf

            <h2 class="text-xl font-bold text-gray-900 mb-4">Form Pembayaran</h2>

            <!-- Info Penting -->
            <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                <p class="text-sm text-blue-900">
                    <strong>ℹ️ Penting:</strong> Setelah Anda mengajukan pembayaran, petugas akan memverifikasi pembayaran Anda. 
                    Denda baru dianggap lunas setelah pembayaran terverifikasi oleh petugas.
                </p>
            </div>

            <!-- Jumlah Bayar -->
            <div class="mb-6">
                <label for="jumlahBayar" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jumlah Pembayaran <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-600">Rp</span>
                    <input type="text" id="jumlahBayarDisplay" placeholder="Contoh: 1.000.000" 
                           class="w-full pl-12 pr-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 @error('jumlah_bayar') border-red-500 @enderror">
                    <input type="hidden" name="jumlah_bayar" id="jumlahBayarInput">
                </div>
                <p class="text-sm text-gray-600 mt-2">Minimal: Rp 1.000 | Maksimal: Rp {{ number_format($loan->denda + 1000, 0, ',', '.') }}</p>
                @error('jumlah_bayar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-6">
                <label for="metodePembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                    Metode Pembayaran <span class="text-red-500">*</span>
                </label>
                <select name="metode_pembayaran" id="metodePembayaran" 
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 @error('metode_pembayaran') border-red-500 @enderror">
                    <option value="">Pilih Metode Pembayaran</option>
                    <option value="tunai">Tunai (Bayar langsung ke petugas)</option>
                    <option value="transfer">Transfer Bank (Upload bukti transfer)</option>
                </select>
                @error('metode_pembayaran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bukti Pembayaran (Conditional) -->
            <div id="buktiContainer" class="mb-6" style="display: none;">
                <label for="buktiPembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                    Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-3">Upload foto/scan bukti transfer (JPG, PNG, GIF - Maksimal 2MB)</p>
                <input type="file" name="bukti_pembayaran" id="buktiPembayaran" accept="image/*"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 @error('bukti_pembayaran') border-red-500 @enderror">
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    ✓ Ajukan Pembayaran
                </button>
                <a href="{{ route('siswa.loans.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg transition text-center">
                    Batal
                </a>
            </div>
        </form>

        <!-- Catatan -->
        <div class="mt-6 bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">
                <strong>📝 Catatan:</strong> Setelah mengajukan pembayaran, petugas akan memverifikasi bukti pembayaran Anda. 
                Denda akan dihapus setelah pembayaran terverifikasi.
            </p>
        </div>
    </div>
</div>

<script>
    // Format Rupiah untuk display field
    document.getElementById('jumlahBayarDisplay').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Hapus semua non-digit
        
        if (value) {
            // Format dengan Intl NumberFormat untuk Rupiah
            let formatted = new Intl.NumberFormat('id-ID').format(value);
            this.value = formatted;
        }
        
        // Update hidden field dengan nilai bersih
        document.getElementById('jumlahBayarInput').value = value;
    });



    // Show/hide bukti pembayaran based on metode
    document.getElementById('metodePembayaran').addEventListener('change', function() {
        const buktiContainer = document.getElementById('buktiContainer');
        const buktiInput = document.getElementById('buktiPembayaran');
        
        if (this.value === 'transfer') {
            buktiContainer.style.display = 'block';
            buktiInput.required = true;
        } else {
            buktiContainer.style.display = 'none';
            buktiInput.required = false;
        }
    });

    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        let displayValue = document.getElementById('jumlahBayarDisplay').value;
        let cleanValue = displayValue.replace(/\D/g, '');
        
        if (!cleanValue || cleanValue === '0') {
            e.preventDefault();
            alert('Jumlah pembayaran harus diisi lebih dari 0');
            return;
        }
        
        // Set clean value ke hidden field sebelum submit
        document.getElementById('jumlahBayarInput').value = cleanValue;
    });
</script>
@endsection
