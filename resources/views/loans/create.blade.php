@extends('layouts.admin')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <h1 class="text-3xl font-bold" style="color: #374151;">Form Peminjaman Alat</h1>
    <a href="{{ route('loans.index') }}" class="px-4 py-2 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
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
    <form action="{{ route('loans.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Nama Peminjam</label>
            <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Pilih Alat</label>
            <select name="tool_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                <option value="">-- Pilih Alat --</option>
                @foreach($tools as $tool)
                    <option value="{{ $tool->id }}" {{ old('tool_id') == $tool->id ? 'selected' : '' }}>
                        {{ $tool->nama_alat }} (Stok: {{ $tool->stok }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Jumlah</label>
            <input type="number" name="jumlah" min="1" value="{{ old('jumlah') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-2 rounded font-semibold transition" style="background-color: #CDEDEA; color: #374151;">
                ✓ Simpan Peminjaman
            </button>
            <a href="{{ route('loans.index') }}" class="flex-1 py-2 rounded font-semibold transition text-center" style="background-color: #CDEDEA; color: #374151;">
                ✕ Batal
            </a>
        </div>
    </form>
</div>

@endsection
