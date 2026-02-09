@extends('layouts.petugas')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Setujui Peminjaman</h1>

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
        @forelse($loans as $index => $loan)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $loan->nama_peminjam }}</td>
            <td class="px-4 py-2">{{ $loan->tool->nama_alat ?? '-' }}</td>
            <td class="px-4 py-2">{{ $loan->jumlah }}</td>
            <td class="px-4 py-2">{{ $loan->tanggal_pinjam }}</td>
            <td class="px-4 py-2">
                @if($loan->status === 'pending')
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold text-sm">Menunggu</span>
                @elseif($loan->status === 'approved')
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold text-sm">Disetujui</span>
                @else
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded font-semibold text-sm">Ditolak</span>
                @endif
            </td>
            <td class="px-4 py-2">
                @if($loan->status === 'pending')
                    <button onclick="openApproveModal({{ $loan->id }}, '{{ $loan->nama_peminjam }}')" class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #CDEDEA; color: #374151; cursor: pointer;">
                        Setujui
                    </button>
                    <button onclick="openRejectModal({{ $loan->id }}, '{{ $loan->nama_peminjam }}')" class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #e74c3c; color: white; cursor: pointer;">
                        Tolak
                    </button>
                @else
                    <span class="px-2 py-1 rounded font-semibold text-sm mr-2" style="background-color: #CDEDEA; color: #374151;">
                        {{ $loan->status === 'approved' ? 'Sudah Disetujui' : 'Ditolak' }}
                    </span>
                    @if($loan->status === 'approved')
                        <a href="{{ route('petugas.cetak-peminjaman', $loan->id) }}" target="_blank" class="px-2 py-1 rounded font-semibold text-sm inline-block" style="background: #CDEDEA; color: #374151; cursor: pointer; margin-top: 4px;">
                            🖨️ Cetak
                        </a>
                    @endif
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-4 text-gray-500">
                Tidak ada peminjaman yang menunggu persetujuan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Modal Approve -->
<div id="approveModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 50;">
    <div style="border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 28rem; margin-left: 1rem; margin-right: 1rem; background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151;">Setujui Peminjaman</h2>
            <button onclick="closeApproveModal()" style="color: #9CA3AF; cursor: pointer; font-size: 1.5rem;">×</button>
        </div>
        <p style="margin-bottom: 1.5rem; color: #374151;">Setujui peminjaman untuk <strong id="approveName"></strong></p>
        <form id="approveForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Durasi Peminjaman (hari):</label>
                <input type="number" name="durasi_hari" min="1" max="90" value="7" required style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: #374151;">
                <small style="color: #6B7280; display: block; margin-top: 0.25rem;">Batas bawah: 1 hari, batas atas: 90 hari</small>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" style="flex: 1; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #CDEDEA; color: #374151; cursor: pointer;">
                    Setujui
                </button>
                <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 50;">
    <div style="border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 28rem; margin-left: 1rem; margin-right: 1rem; background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151;">Tolak Peminjaman</h2>
            <button onclick="closeRejectModal()" style="color: #9CA3AF; cursor: pointer; font-size: 1.5rem;">×</button>
        </div>
        <p style="margin-bottom: 1.5rem; color: #374151;">Tolak peminjaman untuk <strong id="rejectName"></strong>?</p>
        <div style="display: flex; gap: 0.75rem;">
            <form id="rejectForm" method="POST" style="flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" style="width: 100%; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #e74c3c; color: white; cursor: pointer;">
                    Tolak
                </button>
            </form>
            <button onclick="closeRejectModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    const approveRouteTemplate = "{{ route('petugas.approve-loan', ['loan' => ':id']) }}";
    const rejectRouteTemplate = "{{ route('petugas.reject-loan', ['loan' => ':id']) }}";

    function openApproveModal(id, name) {
        document.getElementById('approveName').textContent = name;
        const url = approveRouteTemplate.replace(':id', id);
        document.getElementById('approveForm').action = url;
        document.getElementById('approveModal').style.display = 'flex';
    }

    function closeApproveModal() {
        document.getElementById('approveModal').style.display = 'none';
    }

    function openRejectModal(id, name) {
        document.getElementById('rejectName').textContent = name;
        const url = rejectRouteTemplate.replace(':id', id);
        document.getElementById('rejectForm').action = url;
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    document.getElementById('approveModal').addEventListener('click', function(e) {
        if (e.target === this) closeApproveModal();
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>

@endsection
