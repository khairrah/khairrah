@extends('layouts.admin')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <h1 class="text-3xl font-bold" style="color: #374151;">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}</h1>
    <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
        ← Kembali
    </a>
</div>

@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-400 rounded">
        <ul class="text-red-700">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-8 rounded-lg shadow-lg max-w-2xl" style="background-color: #DCEBFA;">
    <form method="POST" action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" class="space-y-6">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Nama Kategori</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori ?? '') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                   placeholder="Masukkan nama kategori">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                      placeholder="Masukkan deskripsi kategori (opsional)">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-2 rounded font-semibold transition" style="background-color: #CDEDEA; color: #374151;">
                ✓ {{ isset($category) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('categories.index') }}" class="flex-1 py-2 rounded font-semibold transition text-center" style="background-color: #EEF4FF; color: #374151;">
                ✕ Batal
            </a>
        </div>
    </form>
</div>

@endsection
