@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">🏷️ Kategori</h1>

<a href="{{ route('categories.create') }}"
   class="inline-block mb-4 px-4 py-2 rounded font-semibold"
   style="background-color: #CDEDEA; color: #374151;">
   + Tambah Kategori
</a>

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
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('categories.edit', $category->id) }}" class="px-2 py-1 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
                    Edit
                </a>
                <button onclick="openDeleteModal({{ $category->id }}, '{{ $category->nama_kategori }}')" class="px-2 py-1 rounded font-semibold" style="background-color: #e74c3c; color: white;">
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
