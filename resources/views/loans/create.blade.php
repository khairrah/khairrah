@extends('layouts.admin')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <h1 class="text-3xl font-bold" style="color: #374151;">
        Form Peminjaman Buku
    </h1>

    <a href="{{ route('admin.loans.index') }}"
       class="px-4 py-2 rounded font-semibold"
       style="background-color: #CDEDEA; color: #374151;">
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
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-8 rounded-lg shadow-lg max-w-2xl"
     style="background-color: #DCEBFA;">

    <form action="{{ route('admin.loans.store') }}"
          method="POST"
          class="space-y-6">
        @csrf

        <!-- NAMA -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                Nama Peminjam
            </label>
            <input type="text"
                   name="nama_peminjam"
                   value="{{ old('nama_peminjam') }}"
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none">
        </div>

        <!-- PILIH BUKU -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                Pilih Buku
            </label>
            <select name="book_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded">
                <option value="">-- Pilih Buku --</option>

                @foreach($books as $book)
                    <option value="{{ $book->id }}"
                        {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->judul }} (Stok: {{ $book->stok }})
                    </option>
                @endforeach

            </select>
        </div>

        <!-- JUMLAH -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                Jumlah
            </label>
            <input type="number"
                   name="jumlah"
                   min="1"
                   value="{{ old('jumlah') }}"
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded">
        </div>

        <!-- TANGGAL -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                Tanggal Pinjam
            </label>
            <input type="date"
                   name="tanggal_pinjam"
                   value="{{ old('tanggal_pinjam') }}"
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded">
        </div>

        <!-- BUTTON -->
        <div class="flex gap-3 pt-4">

            <!-- SIMPAN -->
            <button type="submit"
                    class="flex-1 py-2 rounded font-semibold"
                    style="background-color: #CDEDEA; color: #374151;">
                ✓ Simpan Peminjaman
            </button>

            <!-- BATAL -->
            <a href="{{ route('admin.loans.index') }}"
               class="flex-1 py-2 rounded font-semibold text-center"
               style="background-color: #CDEDEA; color: #374151;">
                ✕ Batal
            </a>

        </div>

    </form>

</div>

@endsection