@extends('layouts.siswa')

@section('content')

<!-- Header -->
<div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
    <h1 class="text-3xl font-bold drop-shadow-md" style="color: #374151;">
        ➕ Ajukan Pinjaman Buku
    </h1>
    <p class="mt-2 drop-shadow-sm" style="color: #374151;">
        Isi form di bawah untuk meminjam buku yang Anda butuhkan
    </p>
</div>

@if(session('error'))
    <div class="mb-4 p-4 rounded-lg border" style="background-color: #FFE5E5; border-color: #FF6B6B; color: #C92A2A;">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-4 rounded-lg border" style="background-color: #FFE5E5; border-color: #FF6B6B;">
        <ul style="color: #C92A2A;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="rounded-lg shadow-lg p-8 max-w-2xl" style="background-color: #DCEBFA;">
    <form method="POST" action="{{ route('siswa.loans.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Nama Anda</label>
            <input type="text" 
                   value="{{ auth()->user()->name }}" 
                   disabled
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none"
                   style="background-color: #FFF7E6; color: #374151;">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Pilih Buku</label>
            <select name="book_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    style="background-color: #FFF7E6; color: #374151;">
                <option value="">-- Pilih Buku --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->judul }} (Stok: {{ $book->stok }})
                    </option>
                @endforeach
            </select>
            @error('book_id')
                <p style="color: #C92A2A; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Jumlah</label>
            <input type="number" 
                   name="jumlah" 
                   min="1" 
                   value="{{ old('jumlah', 1) }}" 
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                   style="background-color: #FFF7E6; color: #374151;">
            @error('jumlah')
                <p style="color: #C92A2A; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Tanggal Pinjam</label>
            <input type="date" 
                   name="tanggal_pinjam" 
                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                   style="background-color: #FFF7E6; color: #374151;">
            @error('tanggal_pinjam')
                <p style="color: #C92A2A; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" 
                    class="flex-1 py-2 rounded font-semibold transition"
                    style="background-color: #CDEDEA; color: #374151;">
                ✓ Ajukan Pinjaman
            </button>
            <a href="{{ route('siswa.books') }}" 
               class="flex-1 py-2 rounded font-semibold transition text-center"
               style="background-color: #EEF4FF; color: #374151;">
                ✕ Batal
            </a>
        </div>
    </form>
</div>

@endsection