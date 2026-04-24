<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa - UKK Buku</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 font-sans" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside x-cloak
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-[60] transform transition-transform duration-300 ease-in-out md:static md:flex-shrink-0 flex flex-col shadow-sm">
            
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-black text-slate-900 tracking-tight text-xl">UKK Buku</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Student Panel</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-6 mb-6">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border-2 border-white shadow-sm uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Siswa</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto sidebar-scroll">
                <p class="px-4 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Menu Utama</p>

                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                   {{ request()->is('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="text-lg">🏠</span> Dashboard
                </a>

                <a href="{{ route('siswa.books') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                   {{ request()->is('siswa/books*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="text-lg">📦</span> Daftar Buku
                </a>

                <p class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Peminjaman</p>

                <a href="{{ route('siswa.loans.create') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                   {{ request()->is('siswa/loans/create*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="text-lg">➕</span> Ajukan Pinjaman
                </a>

                <a href="{{ route('siswa.loans.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                   {{ request()->is('siswa/loans*') && !request()->is('siswa/loans/create*') && !request()->is('siswa/riwayat*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="text-lg">↩️</span> Kembalikan Buku
                </a>

                <a href="{{ route('siswa.riwayat-peminjaman') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                   {{ request()->is('siswa/riwayat-peminjaman*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="text-lg">📜</span> Riwayat Pinjam
                </a>

                <p class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Akun</p>

                <a href="{{ route('profile.password') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                   {{ request()->is('siswa/profile/password*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="text-lg">🔐</span> Ganti Password
                </a>
            </nav>

            <div class="p-6 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-rose-50 text-rose-600 font-bold text-sm hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 md:px-8 z-50 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-slate-400 font-medium text-sm hidden md:block">
                        Panel Siswa / <span class="text-slate-800 font-bold">@yield('page_title', 'Dashboard')</span>
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-10">
                <div class="max-w-5xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>

    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[55] md:hidden"></div>

</body>
</html>
