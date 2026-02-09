<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body style="background-color: #FFF7E6;">

<div class="flex min-h-screen">

    <!-- SIDEBAR SISWA -->
    <div class="w-64 text-gray-800" style="background-color: #FFF7E6;">
        <div class="p-4 text-xl font-bold border-b" style="background-color: #CDEDEA; color: #374151;">
            Siswa
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('dashboard') }}" 
               class="block px-3 py-2 rounded transition font-semibold border-l-4"
               style="{{ request()->is('dashboard') ? 'background-color: #CDEDEA; color: #374151; border-color: #5B9FFF;' : 'color: #374151; border-color: transparent;' }}">
                🏠 Dashboard
            </a>

            <div class="mt-4 pt-2">
                <p class="text-xs font-bold uppercase" style="color: #374151; opacity: 0.6;">Menu</p>
            </div>

            <a href="{{ route('siswa.tools') }}" 
               class="block px-3 py-2 rounded transition font-semibold border-l-4"
               style="{{ request()->is('tools*') ? 'background-color: #CDEDEA; color: #374151; border-color: #5B9FFF;' : 'color: #374151; border-color: transparent;' }}">
                📦 Daftar Alat
            </a>

            <a href="{{ route('siswa.loans.create') }}" 
               class="block px-3 py-2 rounded transition font-semibold border-l-4"
               style="{{ request()->is('siswa/loans/create*') ? 'background-color: #CDEDEA; color: #374151; border-color: #5B9FFF;' : 'color: #374151; border-color: transparent;' }}">
                ➕ Ajukan Pinjaman
            </a>

            <a href="{{ route('siswa.loans.index') }}" 
               class="block px-3 py-2 rounded transition font-semibold border-l-4"
               style="{{ request()->is('siswa/loans*') && !request()->is('siswa/loans/create*') && !request()->is('siswa/riwayat*') ? 'background-color: #CDEDEA; color: #374151; border-color: #5B9FFF;' : 'color: #374151; border-color: transparent;' }}">
                ↩️ Kembalikan Alat
            </a>

            <a href="{{ route('siswa.riwayat-peminjaman') }}" 
               class="block px-3 py-2 rounded transition font-semibold border-l-4"
               style="{{ request()->is('siswa/riwayat-peminjaman*') ? 'background-color: #CDEDEA; color: #374151; border-color: #5B9FFF;' : 'color: #374151; border-color: transparent;' }}">
                📚 Riwayat Peminjaman
            </a>

            <a href="{{ route('siswa.denda-payments.index') }}" 
               class="block px-3 py-2 rounded transition font-semibold border-l-4"
               style="{{ request()->is('siswa/denda-payments*') ? 'background-color: #CDEDEA; color: #374151; border-color: #5B9FFF;' : 'color: #374151; border-color: transparent;' }}">
                💰 Pembayaran Denda
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="p-4">
            @csrf
            <button class="w-full py-2 rounded" style="background-color: #5B9FFF; color: #FFFFFF;">
                Logout
            </button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-6" style="background-color: #FFF7E6;">
        @yield('content')
    </div>

</div>

</body>
</html>
