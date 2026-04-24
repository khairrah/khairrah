@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-700">🏷️ Kategori</h1>
    <a href="{{ route('categories.create') }}"
       class="px-4 py-2 rounded font-semibold text-sm bg-teal-100 text-teal-800 hover:bg-teal-200 transition shadow-sm border border-teal-200 text-center w-full sm:w-auto">
       + Tambah Kategori
    </a>
</div>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="min-w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">No</th>
            <th class="px-4 py-2" style="color: #374151;">Nama Kategori</th>
            <th class="px-4 py-2" style="color: #374151;">Deskripsi</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $loop->iteration }}</td>
            <td class="px-4 py-2 text-left">{{ $category->nama_kategori }}</td>
            <td class="px-4 py-2 text-left">{{ $category->deskripsi ?? '-' }}</td>
            <td class="px-4 py-2 flex justify-center gap-2">
                <a href="{{ route('categories.edit', $category->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg font-bold text-xs bg-[#dbeafe] text-black hover:bg-[#bfdbfe] transition shadow-sm border border-[#93c5fd]">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <button onclick="openDeleteModal({{ $category->id }}, '{{ $category->nama_kategori }}')" class="inline-flex items-center px-3 py-1.5 rounded-lg font-bold text-xs bg-[#fee2e2] text-black hover:bg-[#fecaca] transition shadow-sm border border-[#fca5a5]">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="px-4 py-2 text-center" style="color: #374151;">Tidak ada data kategori</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm">
        <h2 class="text-lg font-bold mb-4" style="color: #374151;">Konfirmasi Hapus</h2>
        <p id="deleteMessage" class="mb-6" style="color: #374151;">Apakah Anda yakin ingin menghapus kategori ini?</p>
        <form id="deleteForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 rounded font-semibold" style="background-color: #DCEBFA; color: #374151;">
                Batal
            </button>
            <button type="submit" class="flex-1 px-4 py-2 rounded font-semibold text-white" style="background-color: #e74c3c;">
                Hapus
            </button>
        </form>
    </div>
</div>

<script>
function openDeleteModal(id, nama) {
    document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus kategori "${nama}"?`;
    document.getElementById('deleteForm').action = `/categories/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
