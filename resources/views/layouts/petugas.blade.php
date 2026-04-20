<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Petugas - UKK Alat</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    
    <style>
        body {
            background-color: #EFF6FF; /* soft blue background */
        }

        thead {
            background-color: #DBEAFE;
            border-bottom: 2px solid #93C5FD;
        }

        h1, th {
            color: #1E3A8A;
        }

        table td {
            vertical-align: middle;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    </style>
</head>

<body>

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <div class="w-64 text-gray-800 flex flex-col shadow-xl border-r border-blue-200" style="background-color: #EFF6FF;">

        <!-- LOGO -->
        <div class="p-6 text-xl font-bold border-b border-blue-200 flex items-center gap-2" style="background-color: #DBEAFE;">
            📘 UKK Alat
        </div>

        <!-- USER -->
        <div class="p-5 border-b border-blue-200" style="background-color: #E0F2FE;">
            <p class="text-xs text-blue-500 font-bold uppercase">Login sebagai:</p>
            <p class="text-sm text-blue-700 font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-xs text-blue-500">{{ auth()->user()->role }}</p>
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            <!-- DASHBOARD -->
            <div class="mt-2 mb-2 px-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Utama</p>
            </div>

            <a href="{{ route('petugas.dashboard') }}"
               class="block px-4 py-3 rounded-lg font-semibold border-l-4 transition"
               style="{{ request()->is('petugas/dashboard') ? 'background-color: #BFDBFE; color: #1E3A8A; border-color: #3B82F6;' : 'color: #1E3A8A;' }}">
                🏠 Dashboard
            </a>

            <!-- TRANSAKSI -->
            <div class="mt-6 mb-2 px-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Transaksi</p>
            </div>

            <a href="{{ route('petugas.tools') }}"
               class="block px-4 py-3 rounded-lg font-semibold border-l-4 transition"
               style="color: #1E3A8A;">
                📦 Daftar Alat
            </a>

            <a href="{{ route('petugas.approve-loans') }}"
               class="block px-4 py-3 rounded-lg font-semibold border-l-4 transition"
               style="color: #1E3A8A;">
                ✔️ Setujui Peminjaman
            </a>

            <a href="{{ route('petugas.validate-returns') }}"
               class="block px-4 py-3 rounded-lg font-semibold border-l-4 transition"
               style="color: #1E3A8A;">
                ↩️ Pengembalian
            </a>

            <!-- LAPORAN -->
            <div class="mt-6 mb-2 px-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Laporan</p>
            </div>

            <a href="{{ route('petugas.reports') }}"
               class="block px-4 py-3 rounded-lg font-semibold border-l-4 transition"
               style="color: #1E3A8A;">
                📊 Laporan
            </a>

            <a href="{{ route('petugas.laporan-peminjaman') }}"
               class="block px-4 py-3 rounded-lg font-semibold border-l-4 transition"
               style="color: #1E3A8A;">
                📚 Laporan Peminjaman
            </a>

        </nav>

        <!-- LOGOUT -->
        <div class="p-4 border-t border-blue-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full py-3 rounded-lg font-bold text-white transition"
                        style="background-color: #3B82F6;">
                    🚪 Logout
                </button>
            </form>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-8 text-gray-900 overflow-x-auto">
        @yield('content')
    </div>

</div>

</body>
</html>