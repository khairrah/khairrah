@extends('layouts.admin')

@section('content')

<!-- Header -->
<div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
    <h1 class="text-3xl font-bold drop-shadow-md" style="color: #374151;">✏️ Edit Kelas</h1>
    <p class="mt-2 drop-shadow-sm" style="color: #374151;">Ubah informasi kelas</p>
</div>

<!-- Form -->
<div class="rounded-lg shadow-lg p-8 max-w-2xl" style="background-color: #DCEBFA;">
    <form method="POST" action="{{ route('categories.update', $category->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Nama Kelas <span style="color: #FF6B6B;">*</span></label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                   placeholder="Contoh: XII RPL A">
            @error('nama_kategori')
                <p style="color: #C92A2A; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                      placeholder="Deskripsi kelas (opsional)">{{ old('deskripsi', $category->deskripsi) }}</textarea>
            @error('deskripsi')
                <p style="color: #C92A2A; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-2 rounded font-semibold transition" style="background-color: #CDEDEA; color: #374151;">
                ✓ Update Kelas
            </button>
            <a href="{{ route('categories.index') }}" class="flex-1 bg-gray-500 text-white py-2 rounded font-semibold hover:bg-gray-600 transition text-center">
                ✕ Batal
            </a>
        </div>
    </form>
</div>

@endsection
