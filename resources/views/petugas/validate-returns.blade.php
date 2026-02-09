@extends('layouts.petugas')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Validasi Pengembalian</h1>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">No</th>
            <th class="px-4 py-2" style="color: #374151;">Nama Peminjam</th>
            <th class="px-4 py-2" style="color: #374151;">Alat</th>
            <th class="px-4 py-2" style="color: #374151;">Jumlah</th>
            <th class="px-4 py-2" style="color: #374151;">Tanggal Pinjam</th>
            <th class="px-4 py-2" style="color: #374151;">Status Pengembalian</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($loans as $index => $loan)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $loan->nama_peminjam }}</td>
            <td class="px-4 py-2">{{ $loan->tool->nama_alat ?? '-' }}</td>
            <td class="px-4 py-2">{{ $loan->jumlah }}</td>
            <td class="px-4 py-2">{{ $loan->tanggal_pinjam }}</td>
            <td class="px-4 py-2">
                @if($loan->tanggal_kembali)
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold text-sm">Sudah Dikembalikan</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold text-sm">Menunggu</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if(!$loan->tanggal_kembali)
                    <button onclick="openValidateModal({{ $loan->id }}, '{{ $loan->nama_peminjam }}', '{{ addslashes($loan->alasan_siswa) }}', {{ $loan->jumlah }})" class="px-2 py-1 rounded font-semibold text-sm" style="background: #CDEDEA; color: #374151; cursor: pointer;">
                        Validasi
                    </button>
                @else
                    <span class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #CDEDEA; color: #374151;">
                        Sudah Validasi
                    </span>
                    @if($loan->denda > 0 && $loan->denda_status !== 'lunas')
                        <form method="POST" action="{{ route('petugas.mark-denda-lunas', $loan->id) }}" onsubmit="return confirm('Tandai denda sebagai lunas untuk peminjaman ini?')">
                            @csrf
                            <button type="submit" class="px-2 py-1 rounded font-semibold text-sm inline-block" style="background-color: #10B981; color: white; margin-top: 4px;">
                                Mark Lunas
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('petugas.cetak-pengembalian', $loan->id) }}" target="_blank" class="px-2 py-1 rounded font-semibold text-sm inline-block" style="background-color: #3498db; color: white; cursor: pointer; margin-top: 4px;">
                        🖨️ Cetak
                    </a>
                @endif
            </td>
        </tr>
        @empty
        <tr class="border-b">
            <td colspan="7" class="px-4 py-2 text-center" style="color: #6B7280;">Tidak ada pengembalian menunggu validasi</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Modal Validate -->
<div id="validateModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 50; overflow-y: auto;">
    <div style="border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 35rem; margin-left: 1rem; margin-right: 1rem; background-color: #DCEBFA; border: 4px solid #CDEDEA; margin-top: 2rem; margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151;">Validasi Pengembalian</h2>
            <button onclick="closeValidateModal()" style="color: #9CA3AF; cursor: pointer; font-size: 1.5rem;">×</button>
        </div>
        <p style="margin-bottom: 1.5rem; color: #374151;">Validasi pengembalian alat dari <strong id="validateName"></strong></p>
        
        <div style="margin-bottom: 1rem; padding: 0.75rem; background-color: #DBEAFE; border-left: 3px solid #3B82F6; border-radius: 0.375rem;">
            <p style="color: #1E40AF; font-size: 0.9rem; margin: 0;">
                <strong id="jumlahPinjamanInfo"></strong> barang dipinjam. Masukkan jumlah untuk setiap status pengembalian.
            </p>
        </div>

        <form id="validateForm" method="POST">
            @csrf

            <!-- Input Jumlah Baik -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">✓ Jumlah Barang Baik:</label>
                <input type="number" name="jumlah_kembali_baik" id="jumlahBaik" min="0" value="0" 
                       style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: #374151;">
                <small style="color: #6B7280; display: block; margin-top: 0.25rem;">Denda: Rp 5.000/hari keterlambatan</small>
            </div>

            <!-- Input Jumlah Rusak -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">⚠️ Jumlah Barang Rusak:</label>
                <input type="number" name="jumlah_kembali_rusak" id="jumlahRusak" min="0" value="0" 
                       style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: #374151;">
                <small style="color: #6B7280; display: block; margin-top: 0.25rem;">Denda: 50% × Harga Barang</small>
            </div>

            <!-- Input Jumlah Hilang -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">✗ Jumlah Barang Hilang:</label>
                <input type="number" name="jumlah_kembali_hilang" id="jumlahHilang" min="0" value="0" 
                       style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: #374151;">
                <small style="color: #6B7280; display: block; margin-top: 0.25rem;">Denda: 100% × Harga Barang</small>
            </div>

            <!-- Validasi Jumlah -->
            <div id="validasiJumlahContainer" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background-color: #FEE2E2; border-left: 3px solid #DC2626; border-radius: 0.375rem;">
                <p style="color: #7F1D1D; font-size: 0.9rem; margin: 0;" id="validasiJumlahText"></p>
            </div>

            <!-- Input harga untuk rusak/hilang -->
            <div id="hargaBarangContainer" style="display: none; margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Harga Barang (Rp):</label>
                <input type="text" id="hargaBarangDisplay" style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: #374151;" placeholder="Contoh: 1.000.000">
                <input type="hidden" name="harga_barang" id="hargaBarangInput">
                <small style="color: #6B7280; display: block; margin-top: 0.25rem;" id="hargaKeterangan"></small>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" style="flex: 1; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background: #CDEDEA; color: #374151; cursor: pointer;">
                    Validasi
                </button>
                <button type="button" onclick="closeValidateModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let totalJumlahPinjam = 0;

    function openValidateModal(id, name, alasanSiswa = null, jumlahPinjam = 0) {
        totalJumlahPinjam = jumlahPinjam;
        document.getElementById('validateName').textContent = name;
        document.getElementById('jumlahPinjamanInfo').textContent = jumlahPinjam;
        document.getElementById('validateForm').action = `/petugas/loans/${id}/validate-return`;
        document.getElementById('jumlahBaik').value = 0;
        document.getElementById('jumlahRusak').value = 0;
        document.getElementById('jumlahHilang').value = 0;
        document.getElementById('hargaBarangInput').value = '';
        document.getElementById('hargaBarangDisplay').value = '';
        document.getElementById('validateModal').style.display = 'flex';
    }

    function closeValidateModal() {
        document.getElementById('validateModal').style.display = 'none';
    }

    function checkNeedHargaBarang() {
        const jumlahRusak = parseInt(document.getElementById('jumlahRusak').value) || 0;
        const jumlahHilang = parseInt(document.getElementById('jumlahHilang').value) || 0;
        const hargaContainer = document.getElementById('hargaBarangContainer');
        const hargaDisplay = document.getElementById('hargaBarangDisplay');
        
        if (jumlahRusak > 0 || jumlahHilang > 0) {
            hargaContainer.style.display = 'block';
            hargaDisplay.required = true;
        } else {
            hargaContainer.style.display = 'none';
            hargaDisplay.required = false;
        }
    }

    function validateJumlah() {
        const jumlahBaik = parseInt(document.getElementById('jumlahBaik').value) || 0;
        const jumlahRusak = parseInt(document.getElementById('jumlahRusak').value) || 0;
        const jumlahHilang = parseInt(document.getElementById('jumlahHilang').value) || 0;
        const total = jumlahBaik + jumlahRusak + jumlahHilang;
        const validasiContainer = document.getElementById('validasiJumlahContainer');
        const validasiText = document.getElementById('validasiJumlahText');
        
        if (total !== totalJumlahPinjam) {
            validasiContainer.style.display = 'block';
            validasiText.textContent = `⚠️ Total yang dikembalikan (${total}) tidak sesuai dengan jumlah pinjam (${totalJumlahPinjam})!`;
            return false;
        } else {
            validasiContainer.style.display = 'none';
            return true;
        }
    }

    // Event listeners untuk input jumlah
    document.getElementById('jumlahBaik').addEventListener('input', function() {
        validateJumlah();
        checkNeedHargaBarang();
    });
    document.getElementById('jumlahRusak').addEventListener('input', function() {
        validateJumlah();
        checkNeedHargaBarang();
    });
    document.getElementById('jumlahHilang').addEventListener('input', function() {
        validateJumlah();
        checkNeedHargaBarang();
    });

    // Format Rupiah untuk display field
    document.getElementById('hargaBarangDisplay').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Hapus semua non-digit
        
        if (value) {
            let formatted = new Intl.NumberFormat('id-ID').format(value);
            this.value = formatted;
        }
        
        document.getElementById('hargaBarangInput').value = value;
    });

    // Validasi sebelum submit
    document.getElementById('validateForm').addEventListener('submit', function(e) {
        if (!validateJumlah()) {
            e.preventDefault();
            alert('Jumlah barang yang dikembalikan harus sesuai dengan jumlah pinjam!');
        }
    });

    document.getElementById('validateModal').addEventListener('click', function(e) {
        if (e.target === this) closeValidateModal();
    });
</script>

@endsection
