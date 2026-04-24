@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Data Buku</h1>

<a href="{{ route('books.create') }}"
   class="inline-block mb-4 px-4 py-2 rounded font-semibold"
   style="background-color: #CDEDEA; color: #374151;">
   + Tambah Buku
</a>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="min-w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2">Kode Buku</th>
            <th class="px-4 py-2">Judul Buku</th>
            <th class="px-4 py-2">Penulis</th>
            <th class="px-4 py-2">Penerbit</th>
            <th class="px-4 py-2">Tahun</th>
            <th class="px-4 py-2">Stok</th>
            <th class="px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($books as $book)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $book->kode_buku }}</td>
            <td class="px-4 py-2">{{ $book->judul }}</td>
            <td class="px-4 py-2">{{ $book->penulis }}</td>
            <td class="px-4 py-2">{{ $book->penerbit }}</td>
            <td class="px-4 py-2">{{ $book->tahun }}</td>
            <td class="px-4 py-2">
                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">
                    {{ $book->stok }}
                </span>
            </td>
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('books.edit', $book->id) }}"
                   class="px-2 py-1 rounded font-semibold"
                   style="background-color: #CDEDEA; color: #374151;">
                    Edit
                </a>
                <button onclick="openDeleteModal({{ $book->id }}, '{{ $book->judul }}')"
                        class="px-2 py-1 rounded font-semibold"
                        style="background-color: #e74c3c; color: white;">
                    Hapus
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-4 text-gray-500">
                Data buku belum ada
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="rounded-lg shadow-2xl p-6 max-w-sm mx-4" style="background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus</h2>
        <p class="mb-6">Yakin ingin menghapus buku <strong id="bookName"></strong>?</p>

        <div class="flex gap-3">
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 rounded font-semibold" style="background-color: #e74c3c; color: white;">
                    Hapus
                </button>
            </form>
            <button onclick="closeDeleteModal()" class="flex-1 py-2 rounded font-semibold" style="background-color: #CDEDEA;">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, name) {
    document.getElementById('bookName').textContent = name;
    document.getElementById('deleteForm').action = `/books/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection