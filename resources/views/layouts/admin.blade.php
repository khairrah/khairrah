<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - UKK Buku</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #fdfaf1; } /* Light Cream Background */
    </style>
</head>

<body class="text-gray-800 antialiased font-sans" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside x-cloak
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 w-60 bg-white border-r border-gray-100 z-50 transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col shadow-sm">
            
            <!-- LOGO AREA -->
            <div class="p-5 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📦</span>
                    <span class="font-bold text-gray-700 tracking-tight text-lg">UKK Buku</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- USER INFO -->
            <div class="p-5 border-b border-gray-50 bg-[#dceefa]/30">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Administrator</p>
                <p class="text-sm font-bold text-gray-700 truncate">{{ auth()->user()->name }}</p>
            </div>

            <!-- NAVIGATION -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/dashboard') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>🏠</span> Dashboard
                </a>

                <a href="{{ route('books.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/books*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>📚</span> Data Buku
                </a>

                <a href="{{ route('categories.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/categories*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>🏷️</span> Kategori
                </a>

                <p class="px-3 mt-4 mb-1 text-[9px] font-bold text-gray-400 uppercase tracking-widest">Transaksi</p>

                <a href="{{ route('admin.loans.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/loans') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>🔄</span> Peminjaman
                </a>

                <a href="{{ route('admin.loans.approval') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/loans/approval*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>✅</span> Persetujuan
                </a>

                <a href="{{ route('admin.loans.returned') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/loans/returned*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>↩️</span> Pengembalian
                </a>

                <p class="px-3 mt-4 mb-1 text-[9px] font-bold text-gray-400 uppercase tracking-widest">Lainnya</p>

                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/users*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>👥</span> User
                </a>

                <a href="{{ route('admin.profile.password') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/profile/password*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>🔐</span> Ganti Password
                </a>

                <a href="{{ route('activity-logs.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-[13px] font-semibold transition
                   {{ request()->is('admin/activity-logs*') ? 'bg-[#dceefa] text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>📊</span> Log Aktivitas
                </a>
            </nav>

            <!-- LOGOUT -->
            <div class="p-4 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-gray-50 text-gray-600 font-bold text-[12px] hover:bg-red-50 hover:text-red-600 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- TOP BAR (Mobile Only) -->
            <header class="lg:hidden h-14 bg-white border-b border-gray-100 flex items-center justify-between px-4 z-40 flex-shrink-0">
                <button @click="sidebarOpen = true" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-50 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="font-bold text-gray-700 text-sm">UKK Buku</span>
                <div class="w-8"></div>
            </header>

            <!-- CONTENT WRAPPER -->
            <main class="flex-1 overflow-y-auto p-4 md:p-12">
                <div class="max-w-4xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/20 backdrop-blur-[2px] z-40 lg:hidden"></div>

</body>
</html>