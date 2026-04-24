@extends('layouts.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
        Halo, Admin 👋
    </h1>
    <p class="text-[12px] text-gray-400 mt-1">
        Selamat datang di Dashboard Admin, kelola perpustakaan hari ini
    </p>
</div>

<!-- STATISTIK -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <!-- TOTAL PINJAM -->
    <div class="bg-white p-5 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition group">
        <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest leading-none">Total Pinjam</p>
        <h2 class="text-3xl font-bold text-gray-800 mt-1">
            {{ $totalLoans }}
        </h2>
    </div>

    <!-- SEDANG DIPINJAM -->
    <div class="bg-white p-5 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition group">
        <div class="w-10 h-10 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest leading-none">Sedang Dipinjam</p>
        <h2 class="text-3xl font-bold text-gray-800 mt-1">
            {{ $borrowedBooks }}
        </h2>
    </div>

    <!-- TOTAL PENGGUNA -->
    <div class="bg-white p-5 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition group">
        <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest leading-none">Total Pengguna</p>
        <h2 class="text-3xl font-bold text-gray-800 mt-1">
            {{ \App\Models\User::count() }}
        </h2>
    </div>

    <!-- TOTAL BUKU -->
    <div class="bg-white p-5 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition group">
        <div class="w-10 h-10 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest leading-none">Total Buku</p>
        <h2 class="text-3xl font-bold text-gray-800 mt-1">
            {{ $totalBooks }}
        </h2>
    </div>

</div>

<!-- CONTENT TABLES -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- PEMINJAMAN TERBARU -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-gray-700 text-[13px] flex items-center gap-2">
                <span>📌</span> Peminjaman Terbaru
            </h3>
            <a href="{{ route('admin.loans.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>
        
        <div class="space-y-3">
            @forelse($latestLoans as $loan)
                <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-gray-50/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-lg">
                            📖
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-gray-800 leading-tight">{{ $loan->book->judul ?? 'Buku' }}</p>
                            <p class="text-[10px] text-gray-400 font-medium uppercase mt-0.5">{{ $loan->user->name ?? 'User' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-[9px] font-black uppercase">
                        DIPINJAM
                    </span>
                </div>
            @empty
                <p class="text-center py-6 text-gray-400 text-[12px] italic">Belum ada data</p>
            @endforelse
        </div>
    </div>

    <!-- BUKU POPULER -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-gray-700 text-[13px] flex items-center gap-2">
                <span>📚</span> Buku Populer
            </h3>
            <a href="{{ route('books.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @forelse($availableBooks->take(5) as $book)
                <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-gray-50/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-lg">
                            📘
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-gray-800 leading-tight">{{ $book->judul }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $book->category->nama_kategori ?? 'Umum' }} • Stok: {{ $book->stok }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-lg bg-green-50 text-green-600 text-[9px] font-black uppercase border border-green-100">
                        TERSEDIA
                    </span>
                </div>
            @empty
                <p class="text-center py-6 text-gray-400 text-[12px] italic">Belum ada data</p>
            @endforelse
        </div>
    </div>

</div>

@endsection