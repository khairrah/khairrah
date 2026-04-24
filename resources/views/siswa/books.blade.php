@extends('layouts.siswa')

@section('content')

<div class="p-2">
    <!-- HEADER BOX -->
    <div class="bg-[#fef9e7] p-5 rounded-2xl border border-[#f5efd7] shadow-sm mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 text-xl">
                📚
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Daftar Buku</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Temukan buku yang ingin Anda pinjam hari ini</p>
            </div>
        </div>
        
        <!-- FORM PENCARIAN -->
        <form action="{{ route('siswa.books') }}" method="GET" class="w-full sm:w-auto md:ml-auto">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul atau pengarang..." 
                       class="pl-3 pr-8 py-1.5 bg-white border border-gray-200 rounded-xl text-[12px] focus:ring-1 focus:ring-blue-400 outline-none transition w-full sm:w-56 shadow-sm">
                <div class="absolute right-2.5 top-2 text-gray-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-3 border-b bg-gray-50/50">
            <h2 class="font-bold text-gray-500 text-[12px] tracking-wide">Buku Tersedia</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead class="bg-white text-gray-400 text-[10px] uppercase tracking-widest border-b text-left">
                    <tr>
                        <th class="p-3 font-bold">No</th>
                        <th class="p-3 font-bold">Judul</th>
                        <th class="p-3 font-bold">Kategori</th>
                        <th class="p-3 font-bold text-center">Stok</th>
                        <th class="p-3 font-bold text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($books as $index => $book)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-3 font-bold text-gray-700">{{ $book->judul }}</td>
                            <td class="p-3 text-gray-400">{{ $book->category->nama_kategori ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-50 text-blue-600 font-bold text-[10px]">
                                    {{ $book->stok }}
                                </span>
                            </td>
                            <td class="p-3">
                                @if($book->stok > 0)
                                    <span class="px-3 py-1 bg-green-50 text-green-600 text-[9px] font-bold rounded-lg border border-green-100 flex items-center gap-1 justify-center mx-auto w-fit">
                                        TERSEDIA
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-600 text-[9px] font-bold rounded-lg border border-red-100 flex items-center gap-1 justify-center mx-auto w-fit">
                                        HABIS
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-300">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-3xl">📚</span>
                                    <span>Tidak ada buku tersedia</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection