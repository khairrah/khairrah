@extends('layouts.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    
    <div class="flex items-center gap-4">
        <div class="text-3xl">
            📦
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Data Buku
            </h1>
            <p class="text-[12px] text-gray-400">
                Kelola daftar buku yang tersedia di perpustakaan
            </p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
        <!-- FORM PENCARIAN -->
        <form action="{{ route('books.index') }}" method="GET" class="w-full sm:w-auto">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul, kode, atau pengarang..." 
                       class="pl-4 pr-10 py-2.5 bg-gray-50/50 border border-gray-100 rounded-2xl text-[13px] focus:ring-1 focus:ring-blue-300 outline-none transition w-full sm:w-64 shadow-inner">
                <div class="absolute right-3.5 top-3 text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </form>

        <!-- tombol tambah -->
        <a href="{{ route('books.create') }}"
           class="bg-white px-5 py-2.5 rounded-2xl shadow-sm text-[13px] font-bold hover:bg-gray-50 transition border border-gray-100 text-gray-700 text-center whitespace-nowrap">
            + Tambah Buku
        </a>
    </div>

</div>

<!-- CARD TABLE -->
<div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">

    <!-- TITLE BAR -->
    <div class="p-4 border-b border-gray-50 bg-gray-50/30">
        <h2 class="font-bold text-gray-400 text-[11px] uppercase tracking-widest px-1">
            Daftar Buku
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-[13px]">

            <!-- HEAD -->
            <thead class="bg-white text-gray-400 text-[10px] uppercase tracking-widest border-b border-gray-50">
                <tr>
                    <th class="p-4 text-left font-bold">Kode</th>
                    <th class="p-4 text-left font-bold">Judul Buku</th>
                    <th class="p-4 text-left font-bold">Kategori</th>
                    <th class="p-4 text-left font-bold">Pengarang</th>
                    <th class="p-4 text-left font-bold">Penerbit</th>
                    <th class="p-4 text-left font-bold">Tahun</th>
                    <th class="p-4 text-center font-bold">Stok</th>
                    <th class="p-4 text-center font-bold">Aksi</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody class="divide-y divide-gray-50">

            @forelse($books as $book)
                <tr class="hover:bg-gray-50/30 transition">

                    <td class="p-4 text-gray-500 font-medium">
                        {{ $book->kode }}
                    </td>

                    <td class="p-4 font-bold text-gray-700">
                        {{ $book->judul }}
                    </td>

                    <td class="p-4 text-gray-400">
                        {{ $book->category->nama_kategori ?? '-' }}
                    </td>

                    <td class="p-4 text-gray-400">
                        {{ $book->pengarang }}
                    </td>

                    <td class="p-4 text-gray-400">
                        {{ $book->penerbit }}
                    </td>

                    <td class="p-4 text-gray-400">
                        {{ $book->tahun }}
                    </td>

                    <!-- STOK -->
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 font-bold text-[11px]">
                            {{ $book->stok }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">

                            <!-- EDIT -->
                            <a href="{{ route('books.edit', $book->id) }}"
                               class="px-3 py-1.5 rounded-xl bg-[#fef9e7] text-black hover:bg-[#fcf3cf] transition border border-[#f5efd7] font-bold text-[11px]">
                                Edit
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('books.destroy', $book->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus buku ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="px-3 py-1.5 rounded-xl bg-[#fee2e2] text-black hover:bg-[#fecaca] transition border border-[#fca5a5] font-bold text-[11px]">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-center p-12 text-gray-300">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">📂</span>
                            <span class="text-sm font-medium">Tidak ada data buku</span>
                        </div>
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection