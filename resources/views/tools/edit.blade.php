@extends('layouts.admin')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <h1 class="text-3xl font-bold" style="color: #374151;">Edit Alat</h1>
    <a href="{{ route('tools.index') }}" class="px-4 py-2 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
        ← Kembali
    </a>
</div>

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
    <form method="POST" action="{{ route('tools.update', $tool->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Kode Alat</label>
            <input type="text" name="kode_alat" value="{{ $tool->kode_alat }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Nama Alat</label>
            <input type="text" name="nama_alat" value="{{ $tool->nama_alat }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Merk</label>
            <input type="text" name="merk" value="{{ $tool->merk }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Lokasi</label>
            <input type="text" name="lokasi" value="{{ $tool->lokasi }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Kondisi</label>
            <input type="text" name="kondisi" value="{{ $tool->kondisi }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Kategori</label>
            <select name="category_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $tool->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Jurusan</label>
            <select name="jurusan"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                <option value="">-- Pilih Jurusan --</option>
                <option value="TSM" {{ $tool->jurusan == 'TSM' ? 'selected' : '' }}>TSM</option>
                <option value="TKR" {{ $tool->jurusan == 'TKR' ? 'selected' : '' }}>TKR</option>
                <option value="TKJ" {{ $tool->jurusan == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                <option value="RPL" {{ $tool->jurusan == 'RPL' ? 'selected' : '' }}>RPL</option>
                <option value="DKV" {{ $tool->jurusan == 'DKV' ? 'selected' : '' }}>DKV</option>
                <option value="ATPH" {{ $tool->jurusan == 'ATPH' ? 'selected' : '' }}>ATPH</option>
                <option value="APT" {{ $tool->jurusan == 'APT' ? 'selected' : '' }}>APT</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Stok</label>
            <input type="number" name="stok" value="{{ $tool->stok }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tool->tanggal }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-2 rounded font-semibold transition" style="background-color: #CDEDEA; color: #374151;">
                ✓ Perbarui Alat
            </button>
            <a href="{{ route('tools.index') }}" class="flex-1 bg-gray-500 text-white py-2 rounded font-semibold hover:bg-gray-600 transition text-center">
                ✕ Batal
            </a>
        </div>
    </form>
</div>

@endsection
