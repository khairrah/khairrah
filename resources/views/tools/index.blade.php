@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Data Alat</h1>

<a href="{{ route('tools.create') }}"
   class="inline-block mb-4 px-4 py-2 rounded font-semibold"
   style="background-color: #CDEDEA; color: #374151;">
   + Tambah Alat
</a>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="min-w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">Kode</th>
            <th class="px-4 py-2" style="color: #374151;">Nama Alat</th>
            <th class="px-4 py-2" style="color: #374151;">Merk</th>
            <th class="px-4 py-2" style="color: #374151;">Lokasi</th>
            <th class="px-4 py-2" style="color: #374151;">Kondisi</th>
            <th class="px-4 py-2" style="color: #374151;">Kategori</th>
            <th class="px-4 py-2" style="color: #374151;">Jurusan</th>
            <th class="px-4 py-2" style="color: #374151;">Stok</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tools as $tool)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $tool->kode_alat }}</td>
            <td class="px-4 py-2">{{ $tool->nama_alat }}</td>
            <td class="px-4 py-2">{{ $tool->merk }}</td>
            <td class="px-4 py-2">{{ $tool->lokasi }}</td>
            <td class="px-4 py-2">{{ $tool->kondisi }}</td>
            <td class="px-4 py-2">{{ $tool->category->nama_kategori ?? '-' }}</td>
            <td class="px-4 py-2">{{ $tool->jurusan }}</td>
            <td class="px-4 py-2">
                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">
                    {{ $tool->stok }}
                </span>
            </td>
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('tools.edit', $tool->id) }}" class="px-2 py-1 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
                    Edit
                </a>
                <button onclick="openDeleteModal({{ $tool->id }}, '{{ $tool->nama_alat }}')" class="px-2 py-1 rounded font-semibold" style="background-color: #e74c3c; color: white;">
                    Hapus
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center py-4 text-gray-500">
                Data alat belum ada
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="rounded-lg shadow-2xl p-6 max-w-sm mx-4" style="background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold" style="color: #374151;">Konfirmasi Hapus</h2>
            <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
        </div>
        <p class="mb-6" style="color: #374151;">Yakin ingin menghapus alat <strong id="toolName"></strong>?</p>
        <div class="flex gap-3">
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 rounded font-semibold transition" style="background-color: #e74c3c; color: white;">
                    Hapus
                </button>
            </form>
            <button onclick="closeDeleteModal()" class="flex-1 py-2 rounded font-semibold transition" style="background-color: #CDEDEA; color: #374151;">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id, name) {
        document.getElementById('toolName').textContent = name;
        document.getElementById('deleteForm').action = `/tools/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

@endsection
