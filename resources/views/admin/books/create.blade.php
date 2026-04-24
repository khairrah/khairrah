@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="bg-gradient-to-r from-blue-200 to-sky-200 p-6 rounded-xl shadow mb-6">
    <h1 class="text-2xl font-bold text-black">
        ➕ Tambah Buku
    </h1>
    <p class="text-sm text-black">
        Tambahkan data buku baru ke dalam sistem
    </p>
</div>

<!-- FORM -->
<div class="bg-white p-6 rounded-xl border shadow-sm max-w-2xl">

    <!-- ERROR VALIDASI -->
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <div class="space-y-4">

            <!-- KODE -->
            <div>
                <label class="text-sm text-black font-medium">Kode Buku</label>
                <input type="text" name="kode"
                       value="{{ old('kode') }}"
                       class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- JUDUL -->
            <div>
                <label class="text-sm text-black font-medium">Judul Buku</label>
                <input type="text" name="judul"
                       value="{{ old('judul') }}"
                       class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- KATEGORI -->
            <div>
                <label class="text-sm text-black font-medium">Kategori</label>
                <select name="category_id"
                        class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- PENGARANG -->
            <div>
                <label class="text-sm text-black font-medium">Pengarang</label>
                <input type="text" name="pengarang"
                       value="{{ old('pengarang') }}"
                       class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- PENERBIT -->
            <div>
                <label class="text-sm text-black font-medium">Penerbit</label>
                <input type="text" name="penerbit"
                       value="{{ old('penerbit') }}"
                       class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- TAHUN -->
            <div>
                <label class="text-sm text-black font-medium">Tahun</label>
                <input type="number" name="tahun"
                       value="{{ old('tahun') }}"
                       class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- STOK -->
            <div>
                <label class="text-sm text-black font-medium">Stok</label>
                <input type="number" name="stok"
                       value="{{ old('stok') }}"
                       class="w-full border rounded px-3 py-2 text-sm text-black focus:ring-2 focus:ring-blue-400">
            </div>

        </div>

       <!-- BUTTON -->
<div class="flex justify-between items-center mt-6">

<!-- KEMBALI -->
<a href="{{ route('books.index') }}"
   class="px-5 py-2 bg-gray-200 text-black font-semibold rounded-lg shadow hover:bg-gray-300 transition">
   ← Kembali
</a>

<!-- SIMPAN (SOFT BIRU) -->
<button type="submit"
        class="px-6 py-2 bg-blue-200 text-black font-semibold rounded-lg shadow-sm hover:bg-blue-300 transition">
    ✔ Selesai & Simpan Buku
</button>

</div>

    </form>

</div>

@endsection