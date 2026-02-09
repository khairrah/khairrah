@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">↩️ Pengembalian Alat</h1>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">No</th>
            <th class="px-4 py-2" style="color: #374151;">Nama Peminjam</th>
            <th class="px-4 py-2" style="color: #374151;">Alat</th>
            <th class="px-4 py-2" style="color: #374151;">Jumlah</th>
            <th class="px-4 py-2" style="color: #374151;">Tanggal Pinjam</th>
            <th class="px-4 py-2" style="color: #374151;">Status</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($loans as $loan)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $loop->iteration }}</td>
            <td class="px-4 py-2">{{ $loan->nama_peminjam }}</td>
            <td class="px-4 py-2">{{ $loan->tool->nama_alat }}</td>
            <td class="px-4 py-2">{{ $loan->jumlah }}</td>
            <td class="px-4 py-2">{{ $loan->tanggal_pinjam }}</td>
            <td class="px-4 py-2">
                @if($loan->status == 'approved')
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">Sedang Dipinjam</span>
                @elseif($loan->status == 'returned')
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold">Sudah Dikembalikan</span>
                @else
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded font-semibold">{{ ucfirst($loan->status) }}</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if($loan->status == 'approved')
                <button onclick="openReturnModal({{ $loan->id }}, '{{ $loan->nama_peminjam }}', '{{ $loan->tool->nama_alat }}')" 
                        class="px-2 py-1 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
                    Terima Kembali
                </button>
                @else
                <span class="text-gray-500">-</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="px-4 py-2 text-center" style="color: #374151;">Tidak ada peminjaman yang sedang berjalan</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm">
        <h2 class="text-lg font-bold mb-4" style="color: #374151;">Konfirmasi Penerimaan Kembali</h2>
        <p id="returnMessage" class="mb-6" style="color: #374151;"></p>
        <form id="returnForm" method="POST" class="flex gap-2">
            @csrf
            <button type="button" onclick="closeReturnModal()" class="flex-1 px-4 py-2 rounded font-semibold" style="background-color: #DCEBFA; color: #374151;">
                Batal
            </button>
            <button type="submit" class="flex-1 px-4 py-2 rounded font-semibold text-white" style="background-color: #27ae60;">
                Terima Kembali
            </button>
        </form>
    </div>
</div>

<script>
function openReturnModal(id, peminjam, alat) {
    document.getElementById('returnMessage').textContent = `Terima kembali alat "${alat}" dari ${peminjam}?`;
    document.getElementById('returnForm').action = `/returns/${id}`;
    document.getElementById('returnModal').style.display = 'flex';
}

function closeReturnModal() {
    document.getElementById('returnModal').style.display = 'none';
}
</script>

@if(session('success'))
    <div class="mt-4 p-4 bg-green-50 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mt-4 p-4 bg-red-50 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif
@endsection
