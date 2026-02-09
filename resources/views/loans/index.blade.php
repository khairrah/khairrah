@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Data Peminjaman</h1>

<a href="{{ route('loans.create') }}" class="inline-block mb-4 px-4 py-2 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
    + Pinjam Alat
</a>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">Nama</th>
            <th class="px-4 py-2" style="color: #374151;">Alat</th>
            <th class="px-4 py-2" style="color: #374151;">Jumlah</th>
            <th class="px-4 py-2" style="color: #374151;">Tanggal</th>
            <th class="px-4 py-2" style="color: #374151;">Status</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loans as $loan)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $loan->nama_peminjam }}</td>
            <td class="px-4 py-2">{{ $loan->tool->nama_alat }}</td>
            <td class="px-4 py-2">{{ $loan->jumlah }}</td>
            <td class="px-4 py-2">{{ $loan->tanggal_pinjam }}</td>
            <td class="px-4 py-2">
                @if($loan->tanggal_kembali)
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold">Sudah dikembalikan</span>
                @else
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded font-semibold">Dipinjam</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if(!$loan->tanggal_kembali)
                <button onclick="openReturnModal({{ $loan->id }}, '{{ $loan->nama_peminjam }}')" class="px-2 py-1 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
                    Kembalikan
                </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Modal Return -->
<div id="returnModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 50;">
    <div style="border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 28rem; margin-left: 1rem; margin-right: 1rem; background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151;">Konfirmasi Pengembalian</h2>
            <button onclick="closeReturnModal()" style="color: #9CA3AF; cursor: pointer; font-size: 1.5rem;">×</button>
        </div>
        <p style="margin-bottom: 1.5rem; color: #374151;">Kembalikan alat yang dipinjam oleh <strong id="loanName"></strong>?</p>
        <div style="display: flex; gap: 0.75rem;">
            <form id="returnForm" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" style="width: 100%; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #CDEDEA; color: #374151; cursor: pointer;">
                    Kembalikan
                </button>
            </form>
            <button onclick="closeReturnModal()" style="flex: 1; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #CDEDEA; color: #374151; cursor: pointer;">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    function openReturnModal(id, name) {
        document.getElementById('loanName').textContent = name;
        document.getElementById('returnForm').action = `/loans/${id}/return`;
        document.getElementById('returnModal').style.display = 'flex';
    }

    function closeReturnModal() {
        document.getElementById('returnModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('returnModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReturnModal();
        }
    });
</script>

@endsection
